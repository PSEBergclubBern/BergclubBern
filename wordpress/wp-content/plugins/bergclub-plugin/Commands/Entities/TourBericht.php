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
    public $title;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->text = $array['text'];
        $this->date = $array['datum'];
        $this->title = $array['titel'];
    }

    public function __toString()
    {
        return $this->id . "";
    }
}