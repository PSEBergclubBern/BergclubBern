<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 16.03.2017
 * Time: 14:56
 */

namespace BergclubPlugin\MVC;

use BergclubPlugin\MVC\Models\Option;

class Helpers
{

    /**
     * Creates a slug from the given object or class name.
     * @param object|string $class
     * @return string
     */
    public static function getSlug($class){
        if(is_object($class)){
            $class = get_class($class);
        }
        return strtolower(str_replace('\\', '-', $class));
    }

    public static function redirect($url){
        echo '<script type="text/javascript">document.location.href="' . $url . '";</script>';
        exit;
    }

    public static function ensureKeyHasPrefix($key){
        if(substr($key, 0, 4) != "bcb_"){
            $key = "bcb_" . $key;
        }
        return $key;
    }

    public static function ensureKeyHasNoPrefix($key){
        if(substr($key, 0, 4) == "bcb_"){
            $key = substr($key, 4);
        }
        return $key;
    }

    public static function getAddressRoles(){
        $roles = Option::get('bcb_roles');
        return $roles['address'];
    }

    public static function getFunctionaryRoles(){
        $roles = Option::get('bcb_roles');
        return $roles['functionary'];
    }
}