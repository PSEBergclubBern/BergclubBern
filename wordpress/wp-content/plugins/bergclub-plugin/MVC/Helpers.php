<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 16.03.2017
 * Time: 14:56
 */

namespace BergclubPlugin\MVC;

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
}