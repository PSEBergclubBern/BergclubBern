<?php

namespace BergclubPlugin\MVC\Injectors;

/**
 * Allows to inject the class name for the Option to use.
 * <p>
 * Purpose: testing
 *
 * @package BergclubPlugin\Export\Data
 */
trait OptionClassInjector
{
    private static $optionClass = "BergclubPlugin\\MVC\\Models\\Option";

    public function setOptionClass($optionClass)
    {
        static::setOptionClassStatic($optionClass);
    }

    public static function setOptionClassStatic($optionClass)
    {
        if (class_exists($optionClass)) {
            static::$optionClass = $optionClass;
        }
    }

    public function getOptionClass()
    {
        return self::getOptionClassStatic();
    }

    public static function getOptionClassStatic()
    {
        return static::$optionClass;
    }
}