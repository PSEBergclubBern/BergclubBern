<?php
use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;
use BergclubPlugin\MVC\Models\Role;

foreach(User::findAll() as $user){
    /* @var User $user */
    $user->delete();
}

foreach(Role::findByType(Role::TYPE_ADDRESS) as $role){
    /* @var Role $role */
    $role->delete();
}

foreach(Role::findByType(Role::TYPE_FUNCTIONARY) as $role){
    /* @var Role $role */
    $role->delete();
}

Option::remove('bcb_roles');