<?php

namespace BergclubPlugin\Commands;

/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 29.04.17
 * Time: 13:59
 */
class WPCliLogger implements Logger
{
    public function log($message)
    {
        \WP_CLI::log($message);
    }

    public function error($message)
    {
        \WP_CLI::error($message);
    }

    public function debug($message)
    {
        \WP_CLI::debug($message);
    }

    public function success($message)
    {
        \WP_CLI::success($message);
    }

    public function warning($message)
    {
        \WP_CLI::warning($message);
    }
}