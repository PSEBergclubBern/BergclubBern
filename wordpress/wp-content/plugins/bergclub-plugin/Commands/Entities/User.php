<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 09.04.17
 * Time: 20:59
 */

namespace BergclubPlugin\Commands\Entities;


class User
{
    public $id;
    public $login;
    public $email;
    public $password;
    public $firstName;
    public $lastName;
    public $displayName;
    public $canAddTour = false;
    public $canAddMitteilung = false;
    public $canAddAddress = false;

}