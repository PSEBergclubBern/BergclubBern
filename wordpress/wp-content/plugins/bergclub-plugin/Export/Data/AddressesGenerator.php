<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

class AddressesGenerator extends AbstractAddressLineGenerator
{
    protected function getUsers(){
        return User::findAllWithoutSpouse();
    }

    protected function addAdditionalData(&$row, User $user){

    }
}