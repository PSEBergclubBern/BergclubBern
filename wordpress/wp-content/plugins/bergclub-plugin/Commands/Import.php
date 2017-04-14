<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:30
 */

namespace BergclubPlugin\Commands;


use BergclubPlugin\Commands\Entities\Adressen;
use BergclubPlugin\Commands\Entities\Meldung;
use BergclubPlugin\Commands\Entities\Tour;
use BergclubPlugin\Commands\Entities\TourBericht;
use BergclubPlugin\Commands\Entities\User;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User as ModelUser;
use BergclubPlugin\Touren\MetaBoxes\Common;
use \BergclubPlugin\Touren\MetaBoxes\Tour as TourMetaBox;

class Import extends Init
{

    private $noop = false;

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
            \WP_CLI::error('Input file not found, aborting!');
            return;
        }

        // read input file
        require $filename;

        // Check for adressen
        if (!isset($adressen)) {
            \WP_CLI::warning('Input file has no adressen, skipping');
        } else {
            $this->importAddress($adressen);
        }

        // Check for mitteilungen
        if (!isset($mitteilungen)) {
            \WP_CLI::warning('Input file has no mitteilungen, skipping');
        } else {
            $this->importMitteilungen($mitteilungen);
        }

        // Check for touren
        if (!isset($touren) || !isset($berichte)) {
            \WP_CLI::warning('Input file has no touren, berichte... skipping');
        } else {
            $this->importTouren($touren, $berichte);
        }
    }

    function importMitteilungen($mitteilungen)
    {
        \WP_CLI::log('Begin processing of mitteilungen');
        \WP_CLI::log('It has ' . count($mitteilungen) . ' Mitteilungen');

        $list = array();
        foreach ($mitteilungen as $key => $mitteilung) {
            \WP_CLI::debug('Processing element ' . (1+$key));

            $mit = new Entities\Mitteilung();
            $mit->id = $mitteilung['id'];
            $mit->datum = $mitteilung['datum'];
            $mit->titel = $this->convertTitleField($mitteilung['titel']);
            $mit->text = $this->convertTextField($mitteilung['text']);

            \WP_CLI::debug($mit->__toString());
            $list[] = $mit;
        }

        $tempDirectory = sys_get_temp_dir() . '/' . uniqid("mit");
        mkdir ($tempDirectory);
        \WP_CLI::log('Using temp directory "' . $tempDirectory . '" for post processing');

        foreach ($list as $mit) {
            /** @var $mitteilung Entities\Mitteilung */
            touch($tempDirectory . '/' . $mit->id);
            file_put_contents($tempDirectory . '/' . $mit->id, $mit->text);
            if (!$this->noop) {
                \WP_CLI::runcommand("bergclub mitteilung create " . $tempDirectory . "/" . $mit->id . " " .
                        "--title='" . $mit->titel . "' " .
                        "--date='" . $mit->datum . "'
                    ");
            }
        }
    }

    function importTouren($touren, $berichte) {
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
                        'post_date'         => $tourEntity->dateFrom,
                    ),
                    true
                );
                if (!is_numeric($generatedId)) {
                    /** @var \WP_Error $generatedId */
                    \WP_CLI::warning('While creating tour ' . $tourEntity . ': ERROR: ' . $generatedId->get_error_message());
                } else {
                    \WP_CLI::debug('generated tour with id ' . $generatedId);
                    $customFields = array(
                        Common::DATE_FROM_IDENTIFIER    => $tourEntity->dateFrom,
                        Common::DATE_TO_IDENTIFIER      => $tourEntity->dateTo,
                        TourMetaBox::PROGRAM            => $tourEntity->program,
                        TourMetaBox::COSTS              => $tourEntity->costs,
                        TourMetaBox::COSTS_FOR          => $tourEntity->costsFor,
                        TourMetaBox::EQUIPMENT          => $tourEntity->equiptment,
                        TourMetaBox::RISE_UP_METERS     => $tourEntity->up,
                        TourMetaBox::RISE_DOWN_METERS   => $tourEntity->down,
                        TourMetaBox::ADDITIONAL_INFO    => $tourEntity->special,
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

    /**
     * Import method for address
     *
     * @param $addresses array of address
     */
    function importAddress($address) {
        \WP_CLI::log('Begin processing of address');
        \WP_CLI::log('It has ' . count($address) . ' Addresses');

        $addressEntities = array();
        $spouseEntries = array();
        foreach ($address as $a) {
            if ($a['kategorie'] == Adressen::CATEGORY_EHEPAAR) {
                $spouseEntries[] = $a;
            } else {
                $addressEntities[] = $this->generateEntitiesFromArray($a);
            }
        }

        $processedAddresses = array();
        $i = 0;
        foreach ($addressEntities as $addressEntity) {
            $i++;
            /** @var Adressen $addressEntity */
            \WP_CLI::log('Processing ' . $addressEntity);
            $model = new ModelUser($addressEntity->toArray());
            $model->addRole(Role::find($addressEntity->determinateRole()));
            // generate history
            $model->history = $this->generateHistories($addressEntity);
            if (!$this->noop) {
                $model->save();
            }

            $processedAddresses[$addressEntity->id]['entity'] = $addressEntity;
            $processedAddresses[$addressEntity->id]['model'] = $model;
        }

        $i = 0;
        foreach ($spouseEntries as $spouseEntry) {
            $i++;
            \WP_CLI::log('Processing spouses ' . $i);
            $firstNames = mb_split('\+', $spouseEntry['vorname']);
            $lastName = $spouseEntry['name'];

            $firstName1 = trim(current($firstNames));
            $firstName2 = trim(end($firstNames));

            $spouseId1 = null;
            $spouseId2 = null;
            foreach($processedAddresses as $id => $values) {
                if($values['entity']->lastName == $lastName &&
                    $values['entity']->firstName == $firstName1
                    ) {
                    $spouseId1 = $id;
                }

                if($values['entity']->lastName == $lastName &&
                    $values['entity']->firstName == $firstName2
                ) {
                    $spouseId2 = $id;
                }
            }

            if ($spouseId1 && $spouseId2) {
                \WP_CLI::log("Marry " . $processedAddresses[$spouseId1]['entity'] . " and " . $processedAddresses[$spouseId2]['entity']);
                $processedAddresses[$spouseId1]['model']->spouse = $processedAddresses[$spouseId2]['model'];
                $processedAddresses[$spouseId2]['model']->spouse = $processedAddresses[$spouseId1]['model'];
                if (!$this->noop) {
                    $processedAddresses[$spouseId1]['model']->save();
                    $processedAddresses[$spouseId2]['model']->save();
                }
            } else {
                \WP_CLI::warning('couldnt find couple ' . $lastName);
            }
        }
    }

    /**
     * generate address entity from an array key
     *
     * @param $array
     * @return Adressen
     */
    private function generateEntitiesFromArray($a) {
        $addressEntity = new Adressen();
        $addressEntity->id = $a['id'];
        $addressEntity->firstName = trim($a['vorname']);
        $addressEntity->lastName = trim($a['name']);
        $addressEntity->salutation = $a['anrede'];

        $addressEntity->email = $a['email'];
        $addressEntity->ahv = $a['ahv'];
        $addressEntity->birthday = $a['geburtsdatum'];
        $addressEntity->category = $a['kategorie'];
        $addressEntity->number = $a['nr'];
        $addressEntity->phoneBusiness = $a['tel_g'];
        $addressEntity->phoneMobile = $a['natel'];
        $addressEntity->phonePrivate = $a['tel_p'];
        $addressEntity->place = $a['ort'];
        $addressEntity->plz = $a['plz'];
        $addressEntity->street = $a['strasse'];
        $addressEntity->comment = $a['bemerkungen'];
        $addressEntity->addition = $a['zusatz'];

        $addressEntity->freeMemberDate = $a['edat'];
        $addressEntity->freeMemberReason = $a['egrund'];
        $addressEntity->honorMemberDate = $a['fdat'];
        $addressEntity->honorMemberReason = $a['fgrund'];
        $addressEntity->exitDate = $a['adat'];
        $addressEntity->exitReason = $a['agrund'];
        $addressEntity->diedDate = $a['ddat'];
        $addressEntity->diedReason = $a['dgrund'];

        $addressEntity->leader = $a['leiter'];
        $addressEntity->leaderDescription = $a['beschreibung'];
        $addressEntity->leaderFrom = $a['ldat'] == '0000-00-00' ? null : $a['ldat'];
        $addressEntity->leaderTo = $a['lbis'] == '0000-00-00' ? null : $a['lbis'];

        $addressEntity->leaderYouth = $a['js_leiter'];
        $addressEntity->leaderYouthDescription = $a['js_beschreibung'];
        $addressEntity->leaderYouthFrom = $a['jsdat'] == '0000-00-00' ? null : $a['jsdat'];
        $addressEntity->leaderYouthTo = $a['jsbis'] == '0000-00-00' ? null : $a['jsbis'];
        $addressEntity->leaderYouthYear = $a['js_jahr'];
        $addressEntity->leaderYouthPersonNr = $a['pers_nr'];

        $addressEntity->vorstand = $a['vorstand'];
        $addressEntity->vorstandDescription = $a['funktion'];
        $addressEntity->vorstandFrom = $a['vdat'] == '0000-00-00' ? null : $a['vdat'];
        $addressEntity->vorstandTo = $a['vbis'] == '0000-00-00' ? null : $a['vbis'];

        $addressEntity->support = $a['support'];
        $addressEntity->supportFrom = $a['sdat'] == '0000-00-00' ? null : $a['sdat'];;
        $addressEntity->supportTo = $a['sbis'] == '0000-00-00' ? null : $a['sbis'];;
        $addressEntity->supportDescription = $a['aufgabe'];

        $addressEntity->sendProgram = empty($a['versenden']) || $a['versenden'] == 0 ? false : true;

        return $addressEntity;
    }

    /**
     * generate history entry for user
     *
     * Example:
     * <code>
     * $history = [
     *   'bcb_intressent' => [
     *     'date_from' => '2016-10-04',
     *     'date_to' => '2016-11-13',
     *   ],
     *   'bcb_aktivmitglied' => [
     *     'date_from' => '2016-11-13',
     *     'date_to' => null,
     *   ],
     *   'bcb_leiter' => [
     *      'date_from' => '2017-03-26',
     *      'date_to' => null,
     *   ],
     * ];
     * @return array
     */
    private function generateHistories(Adressen $addressEntity) {
        $histories = array();
        if ($addressEntity->leaderFrom != null) {
            $histories = array(
                'bcb_leiter' => array(
                    'date_from' => $addressEntity->leaderFrom
                )
            );
            if (!$addressEntity->leader && $addressEntity->leaderTo != null) {
                $histories['bcb_leiter']['date_to'] = $addressEntity->leaderTo;
            }
        }

        if ($addressEntity->leaderYouthFrom != null) {
            $histories = array(
                'bcb_leiter_jugend' => array(
                    'date_from' => $addressEntity->leaderYouthFrom
                )
            );
            if (!$addressEntity->leaderYouth && $addressEntity->leaderYouthTo != null) {
                $histories['bcb_leiter_jugend']['date_to'] = $addressEntity->leaderYouthTo;
            }
        }

        if ($addressEntity->vorstandFrom != null) {
            // try to find role
            switch($addressEntity->vorstandDescription) {
                case 'Sekretariat':
                    $role = 'sekretariat';
                    break;
                case 'Tourenchef BCB':
                    $role = 'bcb_tourenchef';
                    break;
                case 'Präsident':
                    $role = 'präsident';
                    break;
                case 'Kasse':
                    $role = 'kasse';
                    break;
                case 'Mutationen':
                    $role = 'mutationen';
                    break;
                default:
                    \WP_CLI::warning('Didnt find role ' . $addressEntity->vorstandDescription);
            }
            if (isset($role) && $role != null) {
                $histories = array(
                    $role => array(
                        'date_from' => $addressEntity->vorstandFrom
                    )
                );
                if (!$addressEntity->vorstand && $addressEntity->vorstandTo != null) {
                    $histories[$role]['date_to'] = $addressEntity->vorstandTo;
                }
            }

        }

        if (count($histories) > 1) {
            \WP_CLI::warning('History entry for user ' . $addressEntity . ' could have wrong ordering');
        }

        return $histories;
    }
}