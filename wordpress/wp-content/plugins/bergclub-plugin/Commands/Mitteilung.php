<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 17:50
 */

namespace Commands;


class Mitteilung extends Init
{
    public function create($args, $assoc_args)
    {
        list($filename) = $args;

        \WP_CLI::runcommand("post create '" . $filename . "' \\
            --post_title='" . $assoc_args['post_title'] . "' \\
            --post_date='" . $assoc_args['post_date'] . "'
        ");
    }
}