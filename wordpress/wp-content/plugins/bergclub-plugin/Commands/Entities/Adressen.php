<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 09.04.17
 * Time: 21:12
 */

namespace BergclubPlugin\Commands\Entities;

use BergclubPlugin\MVC\Models\User;

class Adressen implements Entity
{
    const CATEGORY_INSTITUTION = 2;
    const CATEGORY_INSERENT = 3;
    const CATEGORY_EHEPAAR = 4;
    const CATEGORY_INTERESTING_YOUTH = 5;
    const CATEGORY_INTERESTING = 6;
    const CATEGORY_ACTIVE_YOUTH = 7;
    const CATEGORY_ACTIVE = 8;
    const CATEGORY_FREE = 9;
    const CATEGORY_HONOR = 10;
    const CATEGORY_EXIT = 0;
    const CATEGORY_DIED = 1;

    public $id;
    public $category;
    public $firstName;
    public $lastName;
    public $salutation;
    public $number;
    public $street;
    public $plz;
    public $place;
    public $phonePrivate;
    public $phoneBusiness;
    public $phoneMobile;
    public $email;
    public $birthday;
    public $ahv;
    public $comment;
    public $sendProgram = false;
    public $addition;
    /**
     * @var Adressen
     */
    public $spouse;

    public $activeMemberDate;
    public $activeYouthMemberDate;
    public $interessentDate;

    public $leader = false;
    public $leaderDescription;
    public $leaderFrom;
    public $leaderTo;

    public $leaderYouth = false;
    public $leaderYouthDescription;
    public $leaderYouthYear;
    public $leaderYouthFrom;
    public $leaderYouthTo;
    public $leaderYouthPersonNr;

    public $vorstand = false;
    public $vorstandDescription;
    public $vorstandFrom;
    public $vorstandTo;

    public $support = false;
    public $supportDescription;
    public $supportFrom;
    public $supportTo;

    public $freeMemberDate;
    public $freeMemberReason;
    public $honorMemberDate;
    public $honorMemberReason;
    public $exitDate;
    public $exitReason;
    public $diedDate;
    public $diedReason;


    public function __toString()
    {
        return 'ID: ' . $this->id . ' (' . $this->salutation . ' ' . $this->firstName . ' ' . $this->lastName . ')';
    }

    public function isCompany()
    {
        return self::CATEGORY_INSTITUTION == $this->category;
    }

    /**
     * return data as array
     * @return array
     * @see \BergclubPlugin\MVC\Models\User
     */
    public function toArray()
    {
        return array(
            'first_name'        => $this->firstName,
            'last_name'         => $this->lastName,
            'company'           => $this->isCompany() ? $this->lastName : '',
            'street'            => $this->street,
            'zip'               => $this->plz,
            'location'          => $this->place,
            'phone_private'     => $this->phonePrivate,
            'phone_work'        => $this->phoneBusiness,
            'phone_mobile'      => $this->phoneMobile,
            'email'             => $this->email,
            'birthdate'         => $this->birthday,
            'comments'          => $this->comment,
            'program_shipment'  => $this->sendProgram ? '1' : '0',
            'gender'            => $this->salutation == 'Frau' ? 'F' : 'M',
            'address_addition'  => $this->addition,
        );
    }

    /**
     * determinate the role from the user
     *
     * @return string
     */
    public function determinateRole() {
        switch($this->category) {
            case Adressen::CATEGORY_INSTITUTION:
                return 'bcb_institution';
            case Adressen::CATEGORY_ACTIVE_YOUTH:
                return 'bcb_aktivmitglied_jugend';
            case Adressen::CATEGORY_DIED:
            case Adressen::CATEGORY_EXIT:
                return 'bcb_ehemalig';
            case Adressen::CATEGORY_EHEPAAR:
                return null;
            case Adressen::CATEGORY_FREE:
                return 'bcb_freimitglied';
            case Adressen::CATEGORY_HONOR:
                return 'bcb_ehrenmitglied';
            case Adressen::CATEGORY_INSERENT:
                return 'bcb_inserent';
            case Adressen::CATEGORY_INTERESTING:
                return 'bcb_interessent';
            case Adressen::CATEGORY_INTERESTING_YOUTH:
                return 'bcb_interessent_jugend';
            default:
                return 'bcb_aktivmitglied';
        }
    }

    /**
     * @return Adressen
     */
    public function getSpouse()
    {
        return $this->spouse;
    }

    /**
     * @param Adressen $spouse
     */
    public function setSpouse(Adressen $spouse)
    {
        $this->spouse = $spouse;
    }

    /**
     * return the user history as array
     * the format is equal to that from the user model
     *
     * @return array
     * @see User
     */
    public function getUserHistory()
    {
        return array_merge(
            $this->getActiveMemberHistory(),
            $this->getActiveYouthMemberHistory(),
            $this->getFreeMemberHistory(),
            $this->getHonorMemberHistory(),
            $this->getInterestedMemberHistory()
        );
    }

    /**
     * get the history entry for the active member role
     * if the date is not set, it checks if the use has a
     * - leader
     * - support
     * - vorstand
     * - or leader youth role, and takes that date as activemember role.
     *
     * @return array
     */
    private function getActiveMemberHistory()
    {
        $activeMemberDateFrom = null;
        $activeMemberDateTo = null;
        if ($this->activeMemberDate && $this->activeMemberDate != '0000-00-00') {
            $activeMemberDateFrom = new \DateTime($this->activeMemberDate);
        } else {
            $datesActive = array(
                $this->leader ? new \DateTime($this->leaderFrom) : null,
                $this->support ? new \DateTime($this->supportFrom) : null,
                $this->vorstand ? new \DateTime($this->vorstandFrom) : null,
                $this->leaderYouth ? new \DateTime($this->leaderYouthFrom) : null,
            );

            $chk = true;
            $currentLowestDate = new \DateTime();
            foreach ($datesActive as $dateActive) {
                /** @var \DateTime $dateActive */
                if ($dateActive && $currentLowestDate->getTimestamp() > $dateActive->getTimestamp()) {
                    $currentLowestDate = $dateActive;
                    $chk = false;
                }
            }
            if ($chk) {
                return array();
            }
            $activeMemberDateFrom = $currentLowestDate;
        }

        $activeMemberDateTo = $this->getNextActiveDateOrNull($activeMemberDateFrom);

        return array(
            'bcb_aktivmitglied' => array(
                'date_from'  => $activeMemberDateFrom->format('Y-m-d'),
                'date_to'    => $activeMemberDateTo,
            )
        );
    }

    /**
     * get the active youth member history
     *
     * @return array
     */
    private function getActiveYouthMemberHistory()
    {
        if ($this->activeYouthMemberDate && $this->activeYouthMemberDate != '0000-00-00') {
            return array(
                'bcb_aktivmitglied_jugend' => array(
                    'date_from' => $this->activeYouthMemberDate,
                    'date_to'   => $this->getNextActiveDateOrNull(new \DateTime($this->activeYouthMemberDate)),
                )
            );
        }
        return array();
    }

    /**
     * get the history entry for the honor member role
     *
     * @return array
     */
    private function getHonorMemberHistory()
    {
        if ($this->honorMemberDate && $this->honorMemberDate != '0000-00-00') {
            return array(
                'bcb_ehrenmitglied' => array(
                    'date_from' => $this->honorMemberDate,
                    'date_to'   => $this->getNextActiveDateOrNull(new \DateTime($this->honorMemberDate)),
                )
            );
        }
        return array();
    }

    /**
     * get the history entry for the free member role
     *
     * @return array
     */
    private function getFreeMemberHistory()
    {
        if ($this->freeMemberDate && $this->freeMemberDate != '0000-00-00') {
            return array(
                'bcb_freimitglied' => array(
                    'date_from' => $this->freeMemberDate,
                    'date_to'   => $this->getNextActiveDateOrNull(new \DateTime($this->freeMemberDate)),
                )
            );
        }
        return array();
    }

    /**
     * get the history entry for interested in members
     *
     * @return array
     */
    private function getInterestedMemberHistory()
    {
        if ($this->interessentDate && $this->interessentDate != '0000-00-00') {
            return array(
                'bcb_interessent' => array(
                    'date_from' => $this->interessentDate,
                    'date_to'   => $this->getNextActiveDateOrNull(new \DateTime($this->interessentDate)),
                )
            );
        }
        return array();
    }

    /**
     * gives the next date after the current date
     *
     * @param \DateTime|null $currentDate
     * @return null|string
     */
    private function getNextActiveDateOrNull(\DateTime $currentDate = null)
    {
        $candidateTime = PHP_INT_MAX;
        $candidate = null;
        foreach ($this->getAllDatesAsDateTime() as $date) {
            /** @var \DateTime $date */
            if ($date->getTimestamp() <= $currentDate->getTimestamp()) {
                continue;
            }
            $dateIntervall = $date->diff($currentDate);
            if ($dateIntervall->format('s') < $candidateTime) {
                $candidate = $date;
                $candidateTime = $dateIntervall->format('s');
            }
        }

        if ($candidate) {
            return $candidate->format('Y-m-d');
        }
        return null;
    }

    /**
     * get all dates as datetime objects
     *
     * @return array
     */
    private function getAllDatesAsDateTime()
    {
        $dates = array(
            $this->interessentDate && $this->interessentDate != '0000-00-00' ? new \DateTime($this->interessentDate) : null,
            $this->freeMemberDate && $this->freeMemberDate != '0000-00-00' ? new \DateTime($this->freeMemberDate) : null,
            $this->honorMemberDate && $this->honorMemberDate != '0000-00-00' ? new \DateTime($this->honorMemberDate) : null,
            $this->activeYouthMemberDate && $this->activeYouthMemberDate != '0000-00-00' ? new \DateTime($this->activeYouthMemberDate) : null,
            $this->activeMemberDate && $this->activeMemberDate != '0000-00-00' ? new \DateTime($this->activeMemberDate) : null,
            $this->diedDate && $this->diedDate != '0000-00-00' ? new \DateTime($this->diedDate) : null,
            $this->exitDate && $this->exitDate != '0000-00-00' ? new \DateTime($this->exitDate) : null,
        );
        $returnDates = array();
        foreach ($dates as $date) {
            if ($date) {
                $returnDates[] = $date;
            }
        }

        return $returnDates;
    }
}