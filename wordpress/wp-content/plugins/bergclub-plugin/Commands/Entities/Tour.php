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
    public $date;
    public $type;
    public $requirementsTechnical;
    public $requirementsConditional;
    public $leader;
    public $coLeader;


    /**
     * @var TourBericht
     */
    public $tourBericht;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->userId = $array['user_id'];
        $this->dateFrom = $array['von'];
        $this->dateTo = $array['bis'];
        $this->title = $array['titel'];
        $this->master = $array['leiter_a'];
        $this->coMaster = $array['leiter_b'];
        $this->up = $array['auf'];
        $this->down = $array['ab'];
        $this->map = $array['karte'];
        //$this->meetingPoint = $array['treff_o'];
        $this->program = $array['prog'];
        $this->return = $array['rueck_o'];
        $this->equiptment = $array['ausr'];
        $this->food = $array['verpf'];
        $this->costs = $array['kosten'];
        $this->costsFor = $array['fuer'];
        $this->special = $array['besonderes'];
        $this->date = $array['p_date'];
        $this->type = $array['art_id'];
        $this->requirementsTechnical = $array['anf_t'];
        $this->requirementsConditional = $array['anf_k'];
        $this->leader = $array['leiter_a'];
        $this->coLeader = $array['leiter_b'];
    }

    public function __toString()
    {
        return $this->id . ' ' . $this->title . ' ' . $this->dateFrom;
    }
}