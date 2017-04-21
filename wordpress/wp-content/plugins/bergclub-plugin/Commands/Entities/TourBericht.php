<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 03.04.17
 * Time: 12:59
 */

namespace BergclubPlugin\Commands\Entities;

class TourBericht
{
    public $id;
    public $date;
    public $text;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->text = $array['text'];
        $this->date = $array['datum'];
    }

    public function __toString()
    {
        return $this->id . "";
    }
}