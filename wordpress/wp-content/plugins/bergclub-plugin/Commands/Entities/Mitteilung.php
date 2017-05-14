<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:38
 */

namespace BergclubPlugin\Commands\Entities;


class Mitteilung implements Entity
{
    public $id;
    public $datum;
    public $titel;
    public $text;

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