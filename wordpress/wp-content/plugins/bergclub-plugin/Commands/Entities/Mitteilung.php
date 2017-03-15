<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:38
 */

namespace BergclubPlugin\Commands\Entities;


class Mitteilung
{
    public $id;
    public $datum;
    public $titel;
    public $text;

    public function __toString()
    {
        return $this->id . '/' . $this->datum . '/' . $this->titel;
    }
}