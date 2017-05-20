<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 11:21
 */

namespace BergclubPlugin\Export\Data;


trait UserClassInjector
{
    private $userClass = "BergclubPlugin\\MVC\\Models\\User";

    public function setUserClass($userClass){
        if (class_exists($userClass) && isset(class_implements($userClass)["BergclubPlugin\\MVC\\Models\\IUser"])) {
            $this->userClass = $userClass;
        }
    }

    public function getUserClass(){
        return $this->userClass;
    }
}