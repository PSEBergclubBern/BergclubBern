<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 09.04.17
 * Time: 21:12
 */

namespace BergclubPlugin\Commands\Entities;


use BergclubPlugin\MVC\Models\User;

class Adressen
{
    public $id;
    public $firstName;
    public $lastName;
    public $salutation;
    public $number;
    public $category;
    public $street;
    public $plz;
    public $place;
    public $phonePrivate;
    public $phoneBusiness;
    public $phoneMobile;
    public $email;
    public $birthday;
    public $ahv;

    public function __toString()
    {
        return 'ID: ' . $this->id . ' (' . $this->salutation . ' ' . $this->firstName . ' ' . $this->lastName . ')';
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
            'street'            => $this->street,
            'zip'               => $this->plz,
            'location'          => $this->place,
            'phone_private'     => $this->phonePrivate,
            'phone_work'        => $this->phoneBusiness,
            'phone_mobile'      => $this->phoneMobile,
            'email'             => $this->email,
            'birthdate'         => $this->birthday,
            'comments'          => $this->__toString(),
        );
    }

    /**
     *         'leaving_reason' => null,
    'program_shipment' => 1,
    'company' => null,
    'gender' => '',
    'first_name' => null,
    'last_name' => null,
    'address_addition' => null,
    'street' => null,
    'zip' => null,
    'location' => null,
    'phone_private' => null,
    'phone_work' => null,
    'phone_mobile' => null,
    'email' => null,
    'birthdate' => null,
    'comments' => null,
     */
}