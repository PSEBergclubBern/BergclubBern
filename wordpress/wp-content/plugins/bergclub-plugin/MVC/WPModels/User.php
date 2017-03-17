<?php

namespace BergclubPlugin\MVC\WPModels;


class User
{
    private $data = [
        'ID' => null,
        'user_login' => null,
        'user_pass' => null,
        'first_name' => null,
        'last_name' => null,
    ];

    private $custom = [
        'gender' => null,
        'street' => null,
        'zip' => null,
        'location' => null,
        'phone_private' => null,
        'phone_work' => null,
        'phone_mobile' => null,
        'email' => null,
        'type' => null,
    ];

    private static $ignore = [
        'admin',
    ];

    private $roles = [];

    public function __construct(array $data = []){
        $this->fillFromArray($data);
    }

    public static function findAll(){
        $result = get_users();
        $users = [];
        foreach($result as $item){
            if(!in_array($item->data->user_login, self::$ignore)) {
                $user = new User((array)$item->data);
                $metadata = get_user_meta($item->ID);
                foreach ($metadata as $key => $arr) {
                    $user->$key = $arr[0];
                }
                $users[] = $user;
            }
        }

        usort($users, function($a, $b){
            return strcmp($a->last_name.' '.$a->first_name, $b->last_name.' '.$b->first_name);
        });
        return $users;
    }

    public static function find($id){
        $item = get_user_by('ID', $id);
        if($item) {
            if (!in_array($item->data->user_login, self::$ignore)) {
                $user = new User((array)$item->data);
                $metadata = get_user_meta($item->ID);
                foreach ($metadata as $key => $arr) {
                    $user->$key = $arr[0];
                }
                return $user;
            }
        }
        return null;
    }

    public function save(){
        if(empty($this->data['user_login'])){
            $this->data['user_login'] = $this->createLogin();
        }

        $id = wp_insert_user($this->data);

        if (is_wp_error( $id ) ) {
            print_r($id);
            exit;
        }

        $this->id = $id;

        foreach($this->custom as $key => $value){
            update_user_meta($id, $key, $value);
        }
    }

    private function fillFromArray(array $data){
        foreach($data as $key => $value){
            $this->$key = $value;
        }
    }

    private function createLogin(){
        //TODO: check still alright when done
        $login = substr(sanitize_title_with_dashes($this->data['last_name'] . $this->data['first_name']), 0, 8);
        $cnt = '';
        while(username_exists($login . $cnt)){
            $cnt++;
        }
        return $login . $cnt;
    }

    public function __set($key, $value){
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        } elseif (array_key_exists($key, $this->custom)) {
            $this->custom[$key] = $value;
        }
    }

    public function __get($key){
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } elseif (array_key_exists($key, $this->custom)) {
            return $this->custom[$key];
        }
        return null;
    }
}