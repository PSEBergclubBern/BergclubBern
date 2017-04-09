<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 03.04.17
 * Time: 12:59
 */

namespace BergclubPlugin\Commands\Entities;

class Tour
{
    public $id;
    public $userId;
    public $dateFrom;
    public $dateTo;
    public $title;
    public $master;
    public $coMaster;
    public $up;
    public $down;
    public $map;
    public $meetingPoint;
    public $program;
    public $return;
    public $equiptment;
    public $food;
    public $costs;
    public $costsFor;
    public $special;
    private $images = array();

    public function addImage(Image $img) {
        $this->images[] = $img;
    }

    public function getImages() {
        return $this->images;
    }
}