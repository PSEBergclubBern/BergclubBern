<?php

namespace BergclubPlugin\Commands;

/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 29.04.17
 * Time: 13:58
 */
interface Logger
{
    public function log($message);
    public function error($message);
    public function debug($message);
    public function success($message);
}