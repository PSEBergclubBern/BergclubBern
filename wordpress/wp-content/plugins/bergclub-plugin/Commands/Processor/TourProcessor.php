<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 02.05.17
 * Time: 16:17
 */

namespace BergclubPlugin\Commands\Processor;


use BergclubPlugin\Commands\Entities\Entity;
use BergclubPlugin\Commands\Entities\Tour;
use BergclubPlugin\Commands\Entities\TourBericht;
use BergclubPlugin\Touren\MetaBoxes\Common;
use BergclubPlugin\Touren\MetaBoxes\MeetingPoint;
use BergclubPlugin\Touren\MetaBoxes\Tour as TourMetabox;

class TourProcessor extends Processor
{

    protected $processedAddressen = array();

    public function process(...$values): array
    {
        if (count($values) != 5) {
            throw new \RuntimeException();
        }

        $touren = $values[0];
        $berichte = $values[1];
        $art = $this->prepareArt($values[2]);
        $schwierigkeit = $this->prepareSchwierigkeit($values[3]);
        $konditionell = ['', 'Leicht', 'Mittel', 'Schwierig'];
        $adressen = $this->prepareAdressen($values[4]);

        $tourEntities = array();
        foreach ($touren as $tour) {
            $tourEntity = new Tour($tour);

            if (!empty($art[$tourEntity->type])) {
                $tourEntity->type = $art[$tourEntity->type];
            } else {
                $tourEntity->type = '';
            }

            if (!empty($schwierigkeit[$tourEntity->requirementsTechnical])) {
                $tourEntity->requirementsTechnical = $schwierigkeit[$tourEntity->requirementsTechnical];
            } else {
                $tourEntity->requirementsTechnical = '';
            }

            if (!empty($konditionell[$tourEntity->requirementsConditional])) {
                $tourEntity->requirementConditional = $konditionell[$tourEntity->requirementsConditional];
            } else {
                $tourEntity->requirementsConditional = '';
            }

            if (!empty($adressen[$tourEntity->leader])) {
                $tourEntity->leader = $this->findUserIdByName($adressen[$tourEntity->leader]);
            }

            if (!empty($adressen[$tourEntity->coLeader])) {
                $tourEntity->coLeader = $this->findUserIdByName($adressen[$tourEntity->coLeader]);
            }


            $tourEntities[] = $tourEntity;

            $i = 0;
            foreach ($berichte as $bericht) {
                if ($bericht['datum'] == $tourEntity->dateFrom) {
                    $bericht['text'] = $this->convertTextField($bericht['text']);
                    $tourEntity->tourBericht = new TourBericht($bericht);
                    $i++;
                }
            }
            if ($i > 1) {
                $this->logger->warning('Found more than one report for tour ' . $tourEntity . ', taking the last');
            } elseif ($i == 0) {
                $this->logger->warning('Found no report');
            } else {
                $this->logger->log('Found one report');
            }
        }

    }

    public function save(Entity $entity, $noOp = true)
    {
        /** @var $entity Tour */
        $this->logger->log('Processing tour ' . $entity);

        if (!$noOp) {
            $generatedId = wp_insert_post(
                array(
                    'post_title' => $entity->title,
                    'post_author' => 1,
                    'post_status' => 'publish',
                    'post_content' => '-',
                    'post_type' => 'touren',
                    'post_date' => date('Y-01-01', strtotime($entity->dateFrom)),
                ),
                true
            );
            if (!is_numeric($generatedId)) {
                /** @var \WP_Error $generatedId */
                $this->logger->warning('While creating tour ' . $entity . ': ERROR: ' . $generatedId->get_error_message());
            } else {

                $this->logger->debug('generated tour with id ' . $generatedId);
                $customFields = array(
                    Common::DATE_FROM_IDENTIFIER => date('d.m.Y', strtotime($entity->dateFrom)),
                    Common::DATE_TO_IDENTIFIER => date('d.m.Y', strtotime($entity->dateTo)),
                    Common::DATE_FROM_DB => date('Y-m-d', strtotime($entity->dateFrom)),
                    Common::DATE_TO_DB => date('Y-m-d', strtotime($entity->dateTo)),
                    Common::LEADER => $entity->leader,
                    Common::CO_LEADER => $entity->coLeader,
                    Common::IS_ADULT_OR_YOUTH => $entity->isYouth,
                    TourMetabox::PROGRAM => $entity->program,
                    TourMetaBox::COSTS => $entity->costs,
                    TourMetaBox::COSTS_FOR => $entity->costsFor,
                    TourMetaBox::EQUIPMENT => $entity->equiptment,
                    TourMetaBox::RISE_UP_METERS => $entity->up,
                    TourMetaBox::RISE_DOWN_METERS => $entity->down,
                    TourMetaBox::ADDITIONAL_INFO => $entity->special,
                    TourMetaBox::TYPE => $entity->type,
                    TourMetaBox::REQUIREMENTS_TECHNICAL => $entity->requirementsTechnical,
                    TourMetaBox::REQUIREMENTS_CONDITIONAL => $entity->requirementsConditional,
                    TourMetaBox::JSEVENT => $entity->jsEvent,
                    MeetingPoint::FOOD => $entity->food,
                    MeetingPoint::RETURNBACK => $entity->returnBack,
                );

                foreach ($customFields as $key => $value) {
                    update_post_meta($generatedId, $key, $value);
                }

                if ($entity->tourBericht != null) {
                    $generatedReportId = wp_insert_post(
                        array(
                            'post_title'    => $entity->title,
                            'post_author'   => 1,
                            'post_status'   => 'publish',
                            'post_date'     => $entity->tourBericht->date,
                            'post_content'  => $entity->tourBericht->text,
                            'post_type'     => 'tourenberichte',
                        ),
                        true
                    );

                    if (is_numeric($generatedReportId)) {
                        update_post_meta($generatedReportId, '_touren', $generatedId);
                    } else {
                        $this->logger->warning('While creating tourbericht for tour ' . $entity . ': ERROR: ' . $generatedId->get_error_message());
                    }
                }

                $this->logger->success('Finished processing of tour');
            }
        }
    }

    public function getEntityName()
    {
        return 'Touren';
    }


    private function prepareArt($art)
    {
        $tourenTypes = Option::get('tourenarten');
        $result = [];
        foreach ($art as $item) {
            if (isset($item['id']) && isset($item['besch'])) {
                $bcbSlug = array_search($item['besch'], $tourenTypes);
                if ($bcbSlug) {
                    $result[$item['id']] = $bcbSlug;
                }
            }
        }
        return $result;
    }

    private function prepareSchwierigkeit($schwierigkeit)
    {
        $result = [];
        foreach ($schwierigkeit as $item) {
            if (isset($item['id']) && isset($item['schwierigkeit'])) {
                if ($item['schwierigkeit'] == 'keine Angaben') {
                    $item['schwierigkeit'] = '';
                }
                $result[$item['id']] = $item['schwierigkeit'];
            }
        }
        return $result;
    }

    private function prepareAdressen($adressen)
    {
        $result = [];
        foreach ($adressen as $item) {
            if (isset($item['id']) && isset($item['name']) && isset($item['vorname'])) {
                $result[$item['id']] = ['first_name' => $item['vorname'], 'last_name' => $item['name']];
            }
        }
        return $result;
    }

    private function findUserIdByName($args)
    {
        if (!empty($args['first_name']) && !empty($args['last_name'])) {
            $queryArgs = [
                'meta_query' => [
                    [
                        'key' => 'first_name',
                        'value' => $args['first_name'],
                    ],
                    [
                        'key' => 'last_name',
                        'value' => $args['last_name'],
                    ],
                ]
            ];

            $users = get_users($queryArgs);
            if (count($users) == 1) {
                return $users[0]->ID;
            }
        }

        return 0;
    }
}