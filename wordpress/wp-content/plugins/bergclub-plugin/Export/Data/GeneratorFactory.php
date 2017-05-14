<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\Export\Factory;
use BergclubPlugin\MVC\Helpers;

class GeneratorFactory implements Factory
{
    public static function getConcrete($dataSlug)
    {
        $className = __NAMESPACE__ . "\\" . Helpers::snakeToCamelCase($dataSlug) . "Generator";
        if(class_exists($className)){
            return new $className();
        }

        return null;
    }
}