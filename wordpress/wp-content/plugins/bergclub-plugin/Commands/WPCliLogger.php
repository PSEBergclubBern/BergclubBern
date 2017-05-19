<?php

namespace BergclubPlugin\Commands;

/**
 * Class WPCliLogger
 *
 * A wp-cli logger to send messages to the terminal
 *
 * @package BergclubPlugin\Commands
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