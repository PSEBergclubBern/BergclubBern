<?php
namespace BergclubPlugin\MVC\WPModels;

/**
 * A wrapper for the WordPress option.
 *
 * Class Option
 * @package BergclubPlugin\MVC\WPModels
 */
class Option
{
    /**
     * Gets the option with the given key.
     * @param string $key if the key has no 'bcb_' prefix it will be added.
     * @param null|mixed $default the default value to return if key does not exist.
     * @return null|mixed The value of the option or $default if the option is not found.
     */
    public static function get($key, $default = null){
        self::ensureKeyHasPrefix($key);

        return get_option($key, $default);
    }

    /**
     * Creates or updates an option with the given key and value.
     * @param string $key if the key has no 'bcb_' prefix it will be added.
     * @param string $value
     */
    public static function set($key, $value){
        self::ensureKeyHasPrefix($key);

        update_option($key, $value);
    }

    private static function ensureKeyHasPrefix(&$key){
        if(!substr($key, 0, 4) == "bcb_"){
            $key = "bcb_" . $key;
        }
    }
}