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
use BergclubPlugin\Commands\Entities\User;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User as ModelUser;
use BergclubPlugin\Touren\MetaBoxes\Common;

class Import extends Init
{

    /**
     * Import old database files into wordpress.
     *
     * ## OPTIONS
     *
     * <filename>
     * : Filename with the content of the old database. The file should be utf-8 encoded and be a php export of a db.
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

        list($filename) = $args;

        if (!file_exists($filename)) {
            \WP_CLI::error('Input file not found, aborting!');
            return;
        }

        // read input file
        require $filename;

        // Check for mitteilungen
        if (!isset($mitteilungen)) {
            \WP_CLI::warning('Input file has no mitteilungen, skipping');
        } else {
            //$this->importMitteilungen($mitteilungen);
        }

        if (!isset($adressen)) {
            \WP_CLI::warning('Input file has no adressen, skipping');
        } else {
            $this->importAddress($adressen);
        }

        // Check for toueren
        if (!isset($touren) || !isset($galerie) || !isset($bilder)) {
            \WP_CLI::warning('Input file has no touren, galeries or images... skipping');
        } else {
            //$this->importTouren($touren, $galerie, $bilder);
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

            \WP_CLI::runcommand("bergclub mitteilung create " . $tempDirectory . "/" . $mit->id . " " .
                    "--title='" . $mit->titel . "' " .
                    "--date='" . $mit->datum . "'
                ");
        }
    }

    function importTouren($touren, $galeries, $images) {
        \WP_CLI::log('Begin processing of touren');
        \WP_CLI::log('It has ' . count($touren) . ' Touren');

        $tourEntities = array();
        foreach ($touren as $tour) {
            $tourEntity = new Tour();
            $tourEntity->userId = 1;
            $tourEntity->id = $tour['id'];
            $tourEntity->dateFrom = $tour['von'];
            $tourEntity->dateTo = $tour['bis'];
            $tourEntity->title = $tour['titel'];

            $tourEntities[] = $tourEntity;
        }

        foreach ($tourEntities as $tourEntity) {
            /** @var $tourEntity Tour */
            \WP_CLI::log('Processing tour ' . $tourEntity);

            $generatedId = wp_insert_post(
                array(
                    'post_title'        => $tourEntity->title,
                    'post_author'       => 1,
                    'post_status'       => 'publish',
                    'post_content'      => '-',
                    'post_type'         => 'touren',
                ),
                true
            );

            \WP_CLI::debug('generated tour with id ' . $generatedId);

            $customFields = array(
                Common::DATE_FROM_IDENTIFIER    => $tourEntity->dateFrom,
                Common::DATE_TO_IDENTIFIER      => $tourEntity->dateTo,
            );

            foreach ($customFields as $key => $value) {
                update_post_meta($generatedId, $key, $value);
            }

        }
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
            $model->save();

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
                $processedAddresses[$spouseId1]['model']->save();
                $processedAddresses[$spouseId2]['model']->spouse = $processedAddresses[$spouseId1]['model'];
                $processedAddresses[$spouseId2]['model']->save();
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
        $addressEntity->leaderFrom = $a['ldat'];
        $addressEntity->leaderTo = $a['lbis'];

        $addressEntity->leaderYouth = $a['js_leiter'];
        $addressEntity->leaderYouthDescription = $a['js_beschreibung'];
        $addressEntity->leaderYouthFrom = $a['jsdat'];
        $addressEntity->leaderYouthTo = $a['jsbis'];
        $addressEntity->leaderYouthYear = $a['js_jahr'];
        $addressEntity->leaderYouthPersonNr = $a['pers_nr'];

        $addressEntity->vorstand = $a['vorstand'];
        $addressEntity->vorstandDescription = $a['funktion'];
        $addressEntity->vorstandFrom = $a['vdat'];
        $addressEntity->vorstandTo = $a['vbis'];

        $addressEntity->support = $a['support'];
        $addressEntity->supportFrom = $a['sdat'];
        $addressEntity->supportTo = $a['sbis'];
        $addressEntity->supportDescription = $a['aufgabe'];

        $addressEntity->sendProgram = empty($a['versenden']) || $a['versenden'] == 0 ? false : true;

        return $addressEntity;
    }
}