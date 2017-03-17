<?php

namespace BergclubPlugin\MVC\WPModels;


class User
{
    private $meta = [];

    public static function findAll(){
        $result = get_users();
        print "<pre>";
        foreach($result as $item){
            //update_user_meta($item->ID, "test", "Test");
            delete_user_meta($item->ID, "test");
            print_r(get_user_meta($item->ID));
            print_r($item);
        }
        print "</pre>";
        exit;
    }
}