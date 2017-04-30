<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:30
 */

namespace BergclubPlugin\Commands;


use BergclubPlugin\Commands\Entities\Adressen;
use BergclubPlugin\Commands\Entities\Tour;
use BergclubPlugin\Commands\Entities\TourBericht;
use BergclubPlugin\Commands\Processor\AdressenProcessor;
use BergclubPlugin\Commands\Processor\MitteilungProcessor;
use BergclubPlugin\Commands\Processor\Processor;
use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User as ModelUser;
use BergclubPlugin\Touren\MetaBoxes\Common;
use BergclubPlugin\Touren\MetaBoxes\MeetingPoint;
use \BergclubPlugin\Touren\MetaBoxes\Tour as TourMetaBox;

class Import extends Init
{

    private $noop = false;

    /**
     * @var MitteilungProcessor
     */
    private $mitteilungsProcessor;

    /**
     * @var AdressenProcessor
     */
    private $addressenProcessor;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(MitteilungProcessor $mitteilungProcessor = null,
                                AdressenProcessor $adressenProcessor = null,
                                Logger $logger = null)
    {
        if ($logger == null) {
            $this->logger = new WPCliLogger();
        } else {
            $this->logger = $logger;
        }

        if ($mitteilungProcessor == null) {
            $this->mitteilungsProcessor = new MitteilungProcessor($this->logger);
        } else {
            $this->mitteilungsProcessor = $mitteilungProcessor;
        }


        if ($adressenProcessor == null) {
            $this->addressenProcessor = new AdressenProcessor($this->logger);
        } else {
            $this->addressenProcessor = $adressenProcessor;
        }
    }


    /**
     * Import old database files into wordpress.
     *
     * ## OPTIONS
     *
     * <filename>
     * : Filename with the content of the old database. The file should be utf-8 encoded and be a php export of a db.
     *
     * [--noop]
     * : Does not save the generated stuff
     *
     * ---
     * default: success
     * options:
     *   - success
     *   - error
     * ---
     *
     * ## EXAMPLES
     *
     *     wp bergclub import /tmp/import.php
     *
     * @when after_wp_load
     */
    function __invoke($args, $assoc_args)
    {
        if (!is_array($args)) {
            return;
        }
        if (count($args) < 1) {
            return;
        }

        if (isset($assoc_args['noop'])) {
            $this->noop = true;
        }

        list($filename) = $args;

        if (!file_exists($filename)) {
            $this->logger->error('Input file not found, aborting!');
            return;
        }

        // read input file
        require $filename;

        // Check for adressen
        if (!isset($adressen)) {
            $this->logger->warning('Input file has no adressen, skipping');
        } else {
            $this->import($adressen, $this->addressenProcessor);
        }

        // Check for mitteilungen
        if (!isset($mitteilungen)) {
            $this->logger->warning('Input file has no mitteilungen, skipping');
        } else {
            $this->import($mitteilungen, $this->mitteilungsProcessor);
        }

        // Check for touren
        if (!isset($touren) || !isset($berichte) || !isset($art) || !isset($schwierigkeit) || !isset($adressen)) {
            $this->logger->warning('Input file has no touren, berichte, art, schwierigkeit, adressen... skipping');
        } else {
            $this->importTouren($touren, $berichte, $art, $schwierigkeit, $adressen);
        }
    }

    private function import($values, Processor $processor)
    {
        $this->logger->log('Begin processing of ' . $processor->getEntityName());
        $this->logger->log('It has ' . count($values) . ' ' . $processor->getEntityName());

        $entities = $processor->process($values);

        foreach ($entities as $entity) {
            $processor->save($entity, $this->noop);
        }

        $this->logger->success('All ' . $processor->getEntityName() . ' are imported');
    }

    private function prepareArt($art){
        $tourenTypes = Option::get('tourenarten');
        $result = [];
        foreach($art as $item){
            if(isset($item['id']) && isset($item['besch'])){
                $bcbSlug = array_search($item['besch'], $tourenTypes);
                if($bcbSlug) {
                    $result[$item['id']] = $bcbSlug;
                }
            }
        }
        return $result;
    }

    private function prepareSchwierigkeit($schwierigkeit){
        $result = [];
        foreach($schwierigkeit as $item){
            if(isset($item['id']) && isset($item['schwierigkeit'])){
                if($item['schwierigkeit'] == 'keine Angaben'){
                    $item['schwierigkeit'] = '';
                }
                $result[$item['id']] = $item['schwierigkeit'];
            }
        }
        return $result;
    }

    private function prepareAdressen($adressen){
        $result = [];
        foreach($adressen as $item){
            if(isset($item['id']) && isset($item['name']) && isset($item['vorname'])){
                $result[$item['id']] = ['first_name' => $item['vorname'], 'last_name' => $item['name']];
            }
        }
        return $result;
    }

    private function findUserIdByName($args){
        if(!empty($args['first_name']) && !empty($args['last_name'])) {
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
            if(count($users) == 1){
                return $users[0]->ID;
            }
        }

        return 0;
    }

    function importTouren($touren, $berichte, $art, $schwierigkeit, $adressen) {
        $art = $this->prepareArt($art);
        $schwierigkeit = $this->prepareSchwierigkeit($schwierigkeit);
        $konditionell = ['', 'Leicht', 'Mittel', 'Schwierig'];
        $adressen = $this->prepareAdressen($adressen);


        \WP_CLI::log('Begin processing of touren');
        \WP_CLI::log('It has ' . count($touren) . ' Touren');

        $tourEntities = array();
        foreach ($touren as $tour) {
            $tourEntity = new Tour($tour);
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
                \WP_CLI::warning('Found more than one report for tour ' . $tourEntity . ', taking the last');
            } elseif ($i == 0) {
                \WP_CLI::warning('Found no report');
            } else {
                \WP_CLI::log('Found one report');
            }
        }

        foreach ($tourEntities as $tourEntity) {
            /** @var $tourEntity Tour */
            \WP_CLI::log('Processing tour ' . $tourEntity);

            if (!$this->noop) {
                $generatedId = wp_insert_post(
                    array(
                        'post_title'        => $tourEntity->title,
                        'post_author'       => 1,
                        'post_status'       => 'publish',
                        'post_content'      => '-',
                        'post_type'         => 'touren',
                        'post_date'         => date('Y-01-01', strtotime($tourEntity->dateFrom)),
                    ),
                    true
                );
                if (!is_numeric($generatedId)) {
                    /** @var \WP_Error $generatedId */
                    \WP_CLI::warning('While creating tour ' . $tourEntity . ': ERROR: ' . $generatedId->get_error_message());
                } else {
                    if(!empty($art[$tourEntity->type])){
                        $tourEntity->type = $art[$tourEntity->type];
                    }else{
                        $tourEntity->type = '';
                    }

                    if(!empty($schwierigkeit[$tourEntity->requirementsTechnical])){
                        $tourEntity->requirementsTechnical = $schwierigkeit[$tourEntity->requirementsTechnical];
                    }else{
                        $tourEntity->requirementsTechnical = '';
                    }

                    if(!empty($konditionell[$tourEntity->requirementsConditional])){
                        $tourEntity->requirementConditional = $konditionell[$tourEntity->requirementsConditional];
                    }else{
                        $tourEntity->requirementsConditional = '';
                    }

                    if(!empty($adressen[$tourEntity->leader])){
                        $tourEntity->leader = $this->findUserIdByName($adressen[$tourEntity->leader]);
                    }

                    if(!empty($adressen[$tourEntity->coLeader])){
                        $tourEntity->coLeader = $this->findUserIdByName($adressen[$tourEntity->coLeader]);
                    }



                    \WP_CLI::debug('generated tour with id ' . $generatedId);
                    $customFields = array(
                        Common::DATE_FROM_IDENTIFIER            => date('d.m.Y', strtotime($tourEntity->dateFrom)),
                        Common::DATE_TO_IDENTIFIER              => date('d.m.Y', strtotime($tourEntity->dateTo)),
                        Common::DATE_FROM_DB                    => date('Y-m-d', strtotime($tourEntity->dateFrom)),
                        Common::DATE_TO_DB                      => date('Y-m-d', strtotime($tourEntity->dateTo)),
                        Common::LEADER                          => $tourEntity->leader,
                        Common::CO_LEADER                       => $tourEntity->coLeader,
                        Common::IS_ADULT_OR_YOUTH               => $tourEntity->isYouth,
                        TourMetaBox::PROGRAM                    => $tourEntity->program,
                        TourMetaBox::COSTS                      => $tourEntity->costs,
                        TourMetaBox::COSTS_FOR                  => $tourEntity->costsFor,
                        TourMetaBox::EQUIPMENT                  => $tourEntity->equiptment,
                        TourMetaBox::RISE_UP_METERS             => $tourEntity->up,
                        TourMetaBox::RISE_DOWN_METERS           => $tourEntity->down,
                        TourMetaBox::ADDITIONAL_INFO            => $tourEntity->special,
                        TourMetaBox::TYPE                       => $tourEntity->type,
                        TourMetaBox::REQUIREMENTS_TECHNICAL     => $tourEntity->requirementsTechnical,
                        TourMetaBox::REQUIREMENTS_CONDITIONAL   => $tourEntity->requirementsConditional,
                        TourMetaBox::JSEVENT                    => $tourEntity->jsEvent,
                        MeetingPoint::FOOD                      => $tourEntity->food,
                        MeetingPoint::RETURNBACK                => $tourEntity->returnBack,
                    );

                    foreach ($customFields as $key => $value) {
                        update_post_meta($generatedId, $key, $value);
                    }

                    if ($tourEntity->tourBericht != null) {
                        $generatedReportId = wp_insert_post(
                            array(
                                'post_title'        => $tourEntity->title,
                                'post_author'       => 1,
                                'post_status'       => 'publish',
                                'post_date'         => $tourEntity->tourBericht->date,
                                'post_content'      => $tourEntity->tourBericht->text,
                                'post_type'         => 'tourenberichte',
                            ),
                            true
                        );

                        if (is_numeric($generatedReportId)) {
                            update_post_meta($generatedReportId, '_touren', $generatedId);
                        } else {
                            \WP_CLI::warning('While creating tourbericht for tour ' . $tourEntity . ': ERROR: ' . $generatedId->get_error_message());
                        }
                    }

                    \WP_CLI::success('Finished processing of tour');
                }
            }
        }
        \WP_CLI::success('Finished processing of touren');
    }
}