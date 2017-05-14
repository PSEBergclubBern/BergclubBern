<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

abstract class AbstractAddressLineGenerator implements Generator
{
    protected $maxIndex = 0;
    protected $data = [];

    public function getData()
    {
        $data = [];
        $users = [];

        $users = $this->getUsers();

        foreach($users as $user) {
            $this->addRow($user);
        }

        foreach($data as &$row){
            for($i = 1; $i <= $this->maxIndex; $i++){
                if(!isset($row["Adresszeile " . $i])){
                    $row["Adresszeile " . $i] = null;
                }
            }
        }

        return $this->data;
    }

    protected function addRow(User $user){
        $currentIndex = 0;
        $row = [];
        for($i = 1; $i < 7; $i++){
            $row["Adresszeile " . $i] = "";
        }
        $role = $user->address_role->getKey();
        if ($user->company) {
            $currentIndex++;
            $row["Adresszeile " . $currentIndex] = $user->company;
        }

        /* @var User $spouse */
        $spouse = $user->spouse;

        if (!empty(trim($user->first_name . $user->last_name))) {
            if (empty($spouse)) {
                if ($user->company) {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                } else {
                    if ($user->gender) {
                        $currentIndex++;
                        $row["Adresszeile " . $currentIndex] = $user->gender;
                    }
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->first_name . ' ' . $user->last_name);
                }
            } else {
                if ($user->last_name == $spouse->last_name) {
                    $gender = $user->gender;
                    if ($user->gender == $spouse->gender) {
                        $gender .= "en";
                    } else {
                        $gender .= " & " . $spouse->gender;
                    }
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $gender;
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $user->first_name . ' & ' . $spouse->first_name . ' ' . $user->last_name;
                } else {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($spouse->gender . ' ' . $spouse->first_name . ' ' . $spouse->last_name);
                }
            }
        }

        if (!empty($user->address_addition)) {
            $currentIndex++;
            $row["Adresszeile " . $currentIndex] = $user->addressAddition;
        }

        $currentIndex++;
        $row["Adresszeile " . $currentIndex] = $user->street;

        $currentIndex++;
        $row["Adresszeile " . $currentIndex] = trim($user->zip . ' ' . $user->location);

        if($currentIndex > $this->maxIndex){
            $this->maxIndex = $currentIndex;
        }

        $this->addAdditionalData($row, $user);

        $this->data[] = $row;
    }

    abstract protected function addAdditionalData(&$row, User $user);
    abstract protected function getUsers();
}