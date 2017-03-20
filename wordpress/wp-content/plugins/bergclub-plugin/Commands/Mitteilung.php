<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 17:50
 */

namespace BergclubPlugin\Commands;


class Mitteilung extends Init
{

    /**
     * Add a mitteilung.
     *
     * ## OPTIONS
     *
     * <filename>
     * : Filename with the content of the mitteilung.
     *
     * [--title=<title>]
     * : Title of the mitteilung
     *
     * [--date=<date>]
     * : Date of the publishing (YYYY-mm-dd)
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
     *     wp bergclub mitteilung create /tmp/filename --title='test' --date='2015-05-05'
     *
     * @when after_wp_load
     */
    public function create($args, $assoc_args)
    {
        list($filename) = $args;

        $options = array(
            'return'        => true,
            'parse'         => 'json',
            'launch'        => false,
            'exit_error'    => false,
        );

        $terms = \WP_CLI::runcommand('term list category --format=json', $options);

        if (!$this->categoryId) {
            foreach ($terms as $term) {
                if ($term['slug'] == 'category-mitteilungen') {
                    $this->categoryId = $term['term_id'];
                }
            }
        }

        if (!$this->categoryId) {
            \WP_CLI::error('Couldnt find out correct id of mitteilungen');
        } else {
            \WP_CLI::runcommand("post create '" . $filename . "' \\
                --post_title='" . $assoc_args['title'] . "' \\
                --post_date='" . $assoc_args['date'] . "' \\
                --post_category=" . $this->categoryId
            );
        }


    }

    /**
     * Used to store the category of mitteilungen
     * @var int
     */
    private $categoryId;

}