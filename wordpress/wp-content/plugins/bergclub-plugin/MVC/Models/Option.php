<?php
namespace BergclubPlugin\MVC\Models;

/**
 * A wrapper for the WordPress option.
 *
 * Class Option
 * @package BergclubPlugin\MVC\Models
 */
class Option extends AbstractKeyValuePair
{
    protected static $wpUpdateMethod = "update_option";
    protected static $wpDeleteMethod = "delete_option";
    protected static $wpGetMethod = "get_option";

    /**
     * A static set wrapper for the Option object.
     * @param string $key the key for the WP Option to set, needs not to be prefixed with <code>bcb_</code> but will
     * work also if prefixed.
     * @param mixed $value the value to set for the WP Option.
     */
    public static function set($key, $value){
       $option = new Option($key, $value);
       $option->save();
    }

    /**
     * A static get wrapper for the Option object.
     * @param string $key the key for the WP Option to get, needs not to be prefixed with <code>bcb_</code> but will
     * work also if prefixed.
     * @return mixed returns the value of the WP Option if found, null otherwise.
     */
    public static function get($key){
        $option = Option::find($key);
        if($option){
            return $option->getValue();
        }
        return null;
    }
}