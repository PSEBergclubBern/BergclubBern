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
use BergclubPlugin\MVC\Models\Option;

/**
 * Class TourProcessor handles all tours and the reports
 *
 * @package BergclubPlugin\Commands\Processor
 * @author Kevin Studer <kreemer@me.com>
 */
class TourProcessor extends Processor
{

    /**
     * process the tours and tourreports variables and generate entities
     *
     * the values has to be an array with following content
     * $values[0] = $touren variable from the dump
     * $values[1] = $berichte variable from the dump
     * $values[2] = $art variable from the dump
     * $values[3] = $schwierigkeit variable from the dump
     * $values[4] = $adressen variable from the dump
     *
     * @param $values
     * @return array
     */
    public function process($values): array
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

            // Find report
            $reportCandidates = array();
            foreach ($berichte as $bericht) {
                if ($bericht['datum'] == $tourEntity->dateFrom) {
                    $reportCandidates[] = $bericht;
                }
            }

            if (count($reportCandidates) > 1) {
                $this->logger->log('Found more than one report for tour ' . $tourEntity . ', trying to match title');
                $max = -1;
                foreach ($reportCandidates as $reportCandidate) {
                    if (empty($reportCandidate['titel']) && empty($reportCandidate['text'])) {
                        continue;
                    }
                    $currentMax = 0;
                    similar_text($tourEntity->title, $reportCandidate['titel'], $currentMax);
                    if ($max < $currentMax) {
                        $tourEntity->tourBericht = new TourBericht($reportCandidate);
                        $tourEntity->tourBericht->text = $this->convertTextField($tourEntity->tourBericht->text);
                        $max = $currentMax;
                    }
                }
                if ($tourEntity->tourBericht) {
                    $this->logger->success('Matched tour ' . $tourEntity->title . ' with report "' . $tourEntity->tourBericht->title . '"');
                } else {
                    $this->logger->warning('Couldnt match tourreport');
                }
            } elseif (count($reportCandidates) == 0) {
                $this->logger->warning('Found no report');
            } else {
                $this->logger->log('Found one report');
                $tourEntity->tourBericht = new TourBericht(current($reportCandidates));
                $tourEntity->tourBericht->text = $this->convertTextField($tourEntity->tourBericht->text);
            }

            $tourEntities[] = $tourEntity;
        }

        return $tourEntities;
    }

    /**
     * Save the generated entities
     *
     * @param Entity $entity
     * @param bool $noOp
     * @return void
     */
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
                    MeetingPoint::MEETPOINT => $entity->meetingPointKey,
                    MeetingPoint::MEETPOINT_DIFFERENT => $entity->meetingPointKey == MeetingPoint::MEETPOINT_DIFFERENT_KEY ? $entity->meetingPoint : '',
                );

                foreach ($customFields as $key => $value) {
                    update_post_meta($generatedId, $key, $value);
                }

                if ($entity->tourBericht != null) {
                    $generatedReportId = wp_insert_post(
                        array(
                            'post_title' => $entity->title,
                            'post_author' => 1,
                            'post_status' => 'publish',
                            'post_date' => $entity->tourBericht->date,
                            'post_content' => $entity->tourBericht->text,
                            'post_type' => 'tourenberichte',
                        ),
                        true
                    );

                    if (is_numeric($generatedReportId)) {
                        update_post_meta($generatedReportId, '_touren', $generatedId);
                        update_post_meta($generatedReportId, '_isYouth', $entity->isYouth);
                    } else {
                        $this->logger->warning('While creating tourbericht for tour ' . $entity . ': ERROR: ' . $generatedReportId->get_error_message());
                    }
                }

                $this->logger->success('Finished processing of tour');
            }
        }
    }

    /**
     * get the entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'Touren';
    }

    /**
     * get the tourenarten as array with id as key
     *
     * @param $art
     * @return array
     */
    private function prepareArt($art)
    {
        if (count($art) == 0) {
            return array();
        }
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

    /**
     * get the schwierigkeit as array with id as key
     *
     * @param $schwierigkeit
     * @return array
     */
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

    /**
     * read from the adressenvariable the content and generate an array with following content:
     * [ id ] => [ firstname, lastname ]
     *
     * @param $adressen
     * @return array
     */
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

    /**
     * get the wordpress user by first- and lastname
     *
     * @param $args
     * @return int
     */
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