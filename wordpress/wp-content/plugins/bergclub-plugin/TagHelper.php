<?php
namespace BergclubPlugin;


class TagHelper
{
    private static $tags = [];

    /**
     * @param string $key the key for the tag that will be paste in the content (e.g. [bcb_tag] => $key = 'bcb_tag').
     * @param string $method the method that returns the content for the given tag. Needs to be an array for class calls
     * (e.g. [$object, $method])
     */
    public static function addTag($key, $method){
        self::$tags[$key] = $method;
    }

    /**
     * Called on admin_enqueue_scripts hook.
     */
    public static function getTag($key){
        if(!is_array(self::$tags[$key])) {
            if(function_exists(self::$tags[$key])){
                $function = self::$tags[$key];
                return $function();
            }
        }else{
            $arr = self::$tags[$key];
            $obj = $arr[0];
            $method = $arr[1];
            if(method_exists($obj, $method)){
                if(is_string($obj)){
                    return $obj::$method();
                }elseif(is_object($obj)){
                    return $obj->$method();
                }
            }
        }

        return "";
    }

    public static function getKeys(){
        return array_keys(self::$tags);
    }
}