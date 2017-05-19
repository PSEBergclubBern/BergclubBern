<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 17.03.2017
 * Time: 10:46
 */

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Helpers;

/**
 * Represents a key value pair.
 * Holds a key and a value as well as the WP functions to update, delete or get the key value pair.
 *
 * @package BergclubPlugin\MVC\Models
 */
abstract class AbstractKeyValuePair implements IModelSingle
{
    protected static $wpUpdateMethod;
    protected static $wpDeleteMethod;
    protected static $wpGetMethod;
    protected $key;
    protected $value;

    /**
     * Creates a new key value pair
     *
     * @param string $key a key under which the given value is found.
     * @param null|string $value a value to assign to the given key.
     */
    public function __construct($key, $value = null)
    {
        $this->key = self::getModifiedKey($key);
        $this->value = $value;
    }

    private static function getModifiedKey($key)
    {
        $key = Helpers::ensureKeyHasNoPrefix($key);
        $method = static::$wpGetMethod;
        if (empty($method($key))) {
            $key = Helpers::ensureKeyHasPrefix($key);
        }
        return $key;
    }

    /**
     * Calls the stored WP get method to retrieve the key value pair for the given key.
     * @param string $key the key for which the key value pair is to receive.
     * @return null|AbstractKeyValuePair returns a key value pair object or null if the key value pair is not found.
     */
    public static function find($key)
    {
        $key = self::getModifiedKey($key);
        $method = static::$wpGetMethod;
        $value = $method($key);
        $class = get_called_class();
        return new $class($key, $value);
    }

    /**
     * Calls the stored WP update method to save the key value pair if the actual key is not null.
     */
    public function save()
    {
        if (!is_null($this->key)) {
            $method = static::$wpUpdateMethod;
            $method($this->key, $this->value);
        }
    }

    /**
     * Wrapper for the static `remove($key)` method to call directly on the object.
     *
     * @see AbstractKeyValuePair::delete()
     */
    public function delete()
    {
        self::remove($this->key);
        $this->key = null;
        $this->value = null;
    }

    /**
     * Calls the stored WP delete method to remove the key value pair which belongs to the given key.
     * @param string $key the key for the key value pair to remove.
     */
    public static function remove($key)
    {
        $key = self::getModifiedKey($key);
        $method = static::$wpDeleteMethod;
        $method($key);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}