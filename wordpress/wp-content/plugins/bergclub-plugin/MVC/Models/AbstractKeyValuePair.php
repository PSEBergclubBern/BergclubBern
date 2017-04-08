<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 17.03.2017
 * Time: 10:46
 */

namespace BergclubPlugin\MVC\Models;


use BergclubPlugin\MVC\Helpers;

abstract class AbstractKeyValuePair implements IModelSingle
{
    protected $key;
    protected $value;
    protected static $wpUpdateMethod;
    protected static $wpDeleteMethod;
    protected static $wpGetMethod;

    public function __construct($key, $value = null){
        $this->key = self::getModifiedKey($key);
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
        $key = self::getModifiedKey($key);
        $method = static::$wpDeleteMethod;
        $method($key);
    }

    public static function find($key){
        $key = self::getModifiedKey($key);
        $method = static::$wpGetMethod;
        $value = $method($key);
        $class = get_called_class();
        return new $class($key, $value);
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

    private static function getModifiedKey($key){
        $key = Helpers::ensureKeyHasNoPrefix($key);
        $method = static::$wpGetMethod;
        if(empty($method($key))){
            $key = Helpers::ensureKeyHasPrefix($key);
        }
        return $key;
    }
}