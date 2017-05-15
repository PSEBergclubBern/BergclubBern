<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 22:23
 */

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

class MembersGenerator extends AbstractGenerator
{
    public function getData()
    {
        $data = [];
        $users = User::findMitglieder();
        foreach($users as $user){
            /* @var User $user */
            $row['Typ'] = $user->address_role_name;
            $row['Anrede'] = $user->gender;
            $row['Nachname'] = $user->last_name;
            $row['Vorname'] = $user->first_name;
            $row['Zusatz'] = $user->address_addition;
            $row['Strasse'] = $user->street;
            $row['PLZ'] = $user->zip;
            $row['Ort'] = $user->location;
            $row['Telefon (P)'] = $user->phone_private;
            $row['Telefon (G)'] = $user->phone_work;
            $row['Telefon (M)'] = $user->phone_mobile;
            $row['Email'] = $user->email;
            $row['Geburtsdatum'] = $user->birthdate;
            $row['Ehepartner'] = "";

            /* @var User $spouse */
            $spouse = $user->spouse;
            if($spouse){
                $row["Ehepartner"] = $spouse->last_name . ' ' . $spouse->first_name;
            }

            $roles = $user->functionary_roles;
            $arr = [];
            foreach($roles as $role){
                /* @var \BergclubPlugin\MVC\Models\Role $role */
                $arr[] = $role->getName();
            }

            $row['Funktionen'] = join(', ', $arr);
            $data[] = $row;
        }

        return $data;
    }
}