<?php

namespace BergclubPlugin\Commands\Entities;

/**
 * Class Mitteilung
 *
 * This class represents a message from the old website
 *
 * @package BergclubPlugin\Commands\Entities
 */
class Mitteilung implements Entity
{
    public $id;
    public $datum;
    public $titel;
    public $text;

    /**
     * Mitteilung constructor.
     * @param $id       String  the id of the message
     * @param $datum    String  the date of the message in the format yyyy-mm-dd
     * @param $titel    String  the title of the message
     * @param $text     String  the content of the message
     */
    public function __construct($id, $datum, $titel, $text)
    {
        $this->id = $id;
        $this->datum = $datum;
        $this->titel = $titel;
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->id . '/' . $this->datum . '/' . $this->titel;
    }
}