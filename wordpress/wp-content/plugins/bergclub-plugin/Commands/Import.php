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

        // Check for users
        if (!isset($user)) {
            \WP_CLI::warning('Input file has no users, skipping');
        } else {
            $this->importUsers($user);
        }

        // Check for mitteilungen
        if (!isset($mitteilungen)) {
            \WP_CLI::warning('Input file has no mitteilungen, skipping');
        } else {
            $this->importMitteilungen($mitteilungen);
        }

        // Check for toueren
        if (!isset($touren) || !isset($galerie) || !isset($bilder)) {
            \WP_CLI::warning('Input file has no touren, galeries or images... skipping');
        } else {
            $this->importTouren($touren, $galerie, $bilder);
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

    private $importedUsers = array();

    /**
     * Method to import Users
     *
     * @param $users Array of users to import
     */
    function importUsers($users) {
        \WP_CLI::log('Begin processing of users');
        \WP_CLI::log('It has ' . count($users) . ' Users');

        return;

        $userEntities = array();
        foreach ($users as $user) {
            $userEntity = new User();
            $userEntity->id = $user['id'];
            $userEntity->login = $user['login'];
            $userEntity->email = $user['login'] . '@bergclub.ch';
            $userEntity->password = $user['id'];
            $userEntity->firstName = current(mb_split(' ', $user['name']));
            $userEntity->lastName = last(mb_split(' ', $user['name']));
            $userEntity->displayName = $user['login'];

            $userEntities[] = $userEntity;
        }

        foreach ($userEntities as $userEntity) {
            /** @var User $userEntity */

            if ($userEntity->login == 'admin') {
                \WP_CLI::log('Skipping user admin because this user exists already');
                continue;
            }

            \WP_CLI::runcommand("user create " . $userEntity->login . " " . $userEntity->email . " " .
                "--user_pass='" . $userEntity->password . "' " .
                "--display_name='" . $userEntity->displayName . "' " .
                "--first_name='" . $userEntity->firstName . "' " .
                "--last_name='" . $userEntity->lastName . "'"
            );

            $options = array(
                'return'        => true,
                'parse'         => 'json',
                'launch'        => false,
                'exit_error'    => false,
            );

            $userList = \WP_CLI::runcommand("user list --format=json", $options);
            foreach ($userList as $user) {
                if ($user['user_login'] == $userEntity->login) {
                    $this->importedUsers[$userEntity->id] = $user['ID'];
                }
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
        foreach ($address as $a) {
            $addressEntity = new Adressen();

            $addressEntities[] = $addressEntity;
        }

        foreach ($addressEntities as $addressEntity) {
            /** @var Adressen $addressEntity */

        }
    }
}