<?php

namespace BergclubPlugin\Commands\Entities;

/**
 * Class TourBericht
 *
 * This class represents a report to a tour from the old website
 *
 * @package BergclubPlugin\Commands\Entities
 */
class TourBericht
{
    public $id;
    public $date;
    public $text;
    public $title;

    /**
     * TourBericht constructor.
     * @param $array    array   An array with the values of the report
     */
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