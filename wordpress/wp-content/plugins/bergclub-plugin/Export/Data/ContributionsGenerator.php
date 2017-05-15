<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

class ContributionsGenerator extends AbstractAddressLineGenerator
{
    protected function getUsers(){
        $users = User::findMitgliederWithoutSpouse();
        foreach($users as $key => $user){
            /* @var User $user */
            if($user->hasFunctionaryRole()){
                unset($users[$key]);
            }

            /* @var User $spouse */
            $spouse = $user->spouse;

            if(!is_null($spouse)){
                if($spouse->hasFunctionaryRole()){
                    unset($users[$key]);
                }
            }
        }

        return $users;
    }

    protected function addAdditionalData(&$row, User $user){
        $contributions = Option::get('mitgliederbeitraege');
        $contributionType =  $contributions['bcb']['name'];
        $contributionAmount = $contributions['bcb']['amount'];

        if(!is_null($user->spouse)){
            $contributionType =  $contributions['ehepaar']['name'];
            $contributionAmount = $contributions['ehepaar']['amount'];
        }elseif($user->address_role->getKey() == 'bcb_aktivmitglied_jugend'){
            $contributionType =  $contributions['jugend']['name'];
            $contributionAmount = $contributions['jugend']['amount'];
        }

        $row["Beitragstyp"] = $contributionType;
        $row["Betrag"] = number_format($contributionAmount, 2, '.', '');
    }
}