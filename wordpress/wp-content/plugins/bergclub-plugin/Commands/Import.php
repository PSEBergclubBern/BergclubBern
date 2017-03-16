<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:30
 */

namespace BergclubPlugin\Commands;


use BergclubPlugin\Commands\Entities\Meldung;

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
            $this->importMitteilungen($mitteilungen);
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
            $mit->titel = $this->convertTextField($mitteilung['titel']);
            $mit->text = $this->convertTextField($mitteilung['text']);

            \WP_CLI::debug($mit->__toString());
            $list[] = $mit;
        }

        $tempDirectory = sys_get_temp_dir() . '/' . uniqid("mit");
        mkdir ($tempDirectory);
        \WP_CLI::log('Using temp directory "' . $tempDirectory . '" for post processing');

        foreach ($list as $mitteilung) {
            /** @var $mitteilung Entities\Mitteilung */
            touch($tempDirectory . '/' . $mit->id);
            file_put_contents($tempDirectory . '/' . $mit->id, $mit->text);

            \WP_CLI::runcommand("post create " . $tempDirectory . "/" . $mit->id . " \\
                    --post_title='" . $mitteilung->titel . "' \\
                    --post_date='" . $mitteilung->datum . "'
                ");



        }

    }
}