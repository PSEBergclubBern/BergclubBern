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

    /**
     * Generates a JavaScript redirect.
     * Use this to forward the user e.g. after successful validation/task completion to a new page.
     * @param string $url the url you want the user forward to.
     */
    public static function redirect($url){
        echo '<script type="text/javascript">document.location.href="' . $url . '";</script>';
        exit;
    }

    /**
     * Adds prefix 'bcb_' to the given key if prefix not already exists.
     * @param $key
     * @return string
     */
    public static function ensureKeyHasPrefix($key){
        if(substr($key, 0, 4) != "bcb_"){
            $key = "bcb_" . $key;
        }
        return $key;
    }

    /**
     * Removes prefix 'bcb_' from the given key if prefix exists.
     * @param $key
     * @return string
     */
    public static function ensureKeyHasNoPrefix($key){
        if(substr($key, 0, 4) == "bcb_"){
            $key = substr($key, 4);
        }
        return $key;
    }

    /**
     * Converts underscore type names to CamelCase type.
     * e.g. last_name to lastName.
     *
     * @param string $string the name with underscores
     * @param boolean $firstUpperCase if set to true, the first character of the returned string will be uppercase.
     * @return string the given name as CamelCase
     */
    public static function snakeToCamelCase($string, $firstUpperCase = false){
        $result = str_replace('_', '', ucwords($string, '_'));
        if($firstUpperCase){
            $result = strtoupper(substr($result, 0, 1)) . substr($result, 1);
        }
        return $result;
    }


    /**
     * Checks if the given date is a valid date.
     * @param string $date A date to check in the format d.m.Y (day and month can be single- or double-digit)
     * @return bool returns true if date is valid, false otherwise
     */
    public static function isValidDate($date){
        preg_match_all('/^([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})$/', $date, $matches);
        if(count($matches) == 4){
            return checkdate($matches[2][0], $matches[1][0], $matches[3][0]);
        }
        exit;
        return false;
    }
}