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
            $tourEntity->userId = isset($this->importedUsers[$tour['user_id']]) ?
                $this->importedUsers[$tour['user_id']] :
                null;
            $tourEntity->id = $tour['id'];
            $tourEntity->dateFrom = $tour['von'];
            $tourEntity->dateTo = $tour['bis'];
            $tourEntity->title = $tour['titel'];
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
        foreach ($address as $a) {
            $addressEntity = new Adressen();
            $addressEntity->id = $a['id'];
            $addressEntity->firstName = trim($a['vorname']);
            $addressEntity->lastName = trim($a['name']);
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
            $addressEntity->salutation = $a['anrede'];
            $addressEntity->street = $a['strasse'];

            $addressEntities[] = $addressEntity;
        }

        foreach ($addressEntities as $addressEntity) {
            /** @var Adressen $addressEntity */
            \WP_CLI::log('Processing ' . $addressEntity);
            $model = new ModelUser($addressEntity->toArray());
            $model->addRole(Role::find($this->findRoleKey($addressEntity)));
            //$model->save();

        }
    }

    private function findRoleKey(Adressen $addressEntity) {
        return 'bcb_aktivmitglied';
    }
}