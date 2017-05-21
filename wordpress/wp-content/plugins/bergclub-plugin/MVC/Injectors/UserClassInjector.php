<?php

namespace BergclubPlugin\MVC\Injectors;

/**
 * Allows to inject the class name for the User to use.
 * <p>
 * Purpose: testing
 */
trait UserClassInjector
{
    private static $userClass = "BergclubPlugin\\MVC\\Models\\User";

    public function setUserClass($userClass){
        static::setUserClassStatic($userClass);
    }

    public static function setUserClassStatic($userClass){
        if (class_exists($userClass)) {
            static::$userClass = $userClass;
        }
    }

    public function getUserClass(){
        return self::getUserClassStatic();
    }

    public static function getUserClassStatic(){
        return static::$userClass;
    }
}