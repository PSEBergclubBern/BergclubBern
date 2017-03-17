<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 17.03.2017
 * Time: 10:46
 */

namespace BergclubPlugin\MVC\WPModels;


abstract class AbstractKeyValuePair
{
    protected $key;
    protected $value;
    protected static $wpUpdateMethod;
    protected static $wpDeleteMethod;
    protected static $wpGetMethod;

    public function __construct($key, $value = null){
        self::ensureKeyHasPrefix($key);
        $this->key = $key;
        $this->value = $value;
    }

    public function save(){
        if(!is_null($this->key)) {
            $method = static::$wpUpdateMethod;
            $method($this->key, $this->value);
        }
    }

    public function delete(){
        self::remove($this->key);
        $this->key = null;
        $this->value = null;
    }

    public static function remove($key){
        self::ensureKeyHasPrefix($key);
        $method = static::$wpDeleteMethod;
        $method($key);
    }

    public static function find($key){
        self::ensureKeyHasPrefix($key);
        $method = static::$wpGetMethod;
        $value = $method($key);
        $class = get_called_class();
        return new $class($key, $value);
    }

    private static function ensureKeyHasPrefix(&$key){
        if(!substr($key, 0, 4) == "bcb_"){
            $key = "bcb_" . $key;
        }
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}