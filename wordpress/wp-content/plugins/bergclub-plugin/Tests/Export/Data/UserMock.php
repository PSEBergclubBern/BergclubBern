<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\MVC\Models\IUser;

/**
 * Mocks the needed User functionality
 *
 * @package BergclubPlugin\Tests\Export\Data
 */
class UserMock implements IUser
{
    public static $findCurrent;
    public static $find;
    public static $findAllWithoutSpouse = [];
    public static $findByLogin;
    public static $findMitgliederWithoutSpouse = [];
    public static $findMitglieder;
    public static $findByRoles;
    public static $findByRole;

    public $program_shipment;
    public $company;
    public $gender;
    public $first_name;
    public $last_name;
    public $address_addition;
    public $street;
    public $zip;
    public $location;
    public $phone_private;
    public $phone_work;
    public $phone_mobile;
    public $email;
    public $main_address;
    public $roles = [];
    public $spouse;
    public $raw_program_shipment;


    public static function findCurrent()
    {
        return static::$findCurrent;
    }

    public static function find($id, $allowWpUsers = false)
    {
        return static::$find;
    }

    public static function findAllWithoutSpouse()
    {
        return static::$findAllWithoutSpouse;
    }

    public static function findByLogin($login)
    {
        return static::$findByLogin;
    }

    public static function findMitgliederWithoutSpouse()
    {
        return static::$findMitgliederWithoutSpouse;
    }

    public static function findMitglieder()
    {
        return static::$findMitglieder;
    }

    public static function findByRoles(array $roleList)
    {
        return static::$findByRoles;
    }

    public static function findByRole($role)
    {
        return static::$findByRole;
    }
}