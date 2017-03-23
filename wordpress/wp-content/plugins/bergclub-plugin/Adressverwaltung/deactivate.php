<?php
use BergclubPlugin\MVC\Models\Option;

$users = \BergclubPlugin\MVC\Models\User::findAll();
foreach($users as $user){
    /* @var \BergclubPlugin\MVC\Models\User $user */
    $user->delete();
}

Option::remove('roles');
Option::remove('capabilities');
