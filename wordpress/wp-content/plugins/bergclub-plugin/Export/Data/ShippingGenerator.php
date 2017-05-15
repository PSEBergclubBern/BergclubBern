<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

class ShippingGenerator extends AbstractAddressLineGenerator
{
    protected function getUsers(){
        $users = User::findAllWithoutSpouse();
        foreach($users as $key => $user){
            /* @var User $user */
            if(!$user->raw_program_shipment){
                unset($users[$key]);
            }
        }

        return $users;
    }

    protected function addAdditionalData(&$row, User $user){

    }
}