<?php

namespace BergclubPlugin\Commands;

use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User;

class PseudoUser extends Init
{

    /**
     * create pseudo users for functionary roles.
     *
     * ## OPTIONS
     *
     * <filename>
     * : Filename with the users array (key 'user' with user properties array, key 'address_role' with the address role slug
     * and key 'functionary_role' (optional) with the functionary role.
     *
     * @when after_wp_load
     */
    function __invoke($args, $assoc_args)
    {
        if (!is_array($args)) {
            return;
        }
        if (count($args) < 1) {
            return;
        }

        if (isset($assoc_args['noop'])) {
            $this->noop = true;
        }

        list($filename) = $args;

        if (!file_exists($filename)) {
            \WP_CLI::error('Input file not found, aborting!');
            return;
        }

        require $filename;

        if(empty($pseudoUsers) || !is_array($pseudoUsers)){
            \WP_CLI::error('Data format incorrect!');
            return;
        }

        foreach($pseudoUsers as $pseudoUser){
            if(is_array($pseudoUser)){
                $this->createUser($pseudoUser);
            }
        }
    }

    function createUser(array $userData){
        if(!empty($userData['user']) && !empty($userData['address_role']) && is_array($userData['user'])){
            if(!empty($userData['user']['user_login'])) {
                $existingUser = User::findByLogin($userData['user']['user_login']);
                if ($existingUser) {
                    \WP_CLI::warning('Could not create user ' . $userData['user']['user_login'] . ' - user already exists.');
                    return;
                }
            }

            $user = new User();
            foreach($userData['user'] as $key => $value){
                $user->$key = $value;
            }

            $role = Role::find($userData['address_role']);
            if(!$role){
                \WP_CLI::warning('Could not create user, address role ' . $userData['address_role'] . ' does not exist.');
                return;
            }
            $user->addRole($role);
            if(isset($userData['functionary_role'])){
                $role = Role::find($userData['functionary_role']);
                if(!$role){
                    \WP_CLI::warning('Could not create user, functionary role ' . $userData['functionary_role'] . ' does not exist.');
                    return;
                }
                $user->addRole($role);
            }

            $user->save();
            \WP_CLI::success('User ' . $user->user_login . ' created.');
        }
    }
}