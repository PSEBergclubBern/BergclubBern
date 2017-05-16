<?php

namespace BergclubPlugin\Commands;

/**
 * Interface Logger
 *
 * Interface for the logger
 *
 * @package BergclubPlugin\Commands
 */
interface Logger
{
    public function log($message);

    public function error($message);

    public function debug($message);

    public function success($message);

    public function warning($message);
}