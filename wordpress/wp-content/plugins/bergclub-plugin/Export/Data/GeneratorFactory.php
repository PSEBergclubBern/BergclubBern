<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\Export\Factory;
use BergclubPlugin\MVC\Helpers;

class GeneratorFactory implements Factory
{
    public static function getConcrete($dataSlug, array $args = [])
    {
        $className = __NAMESPACE__ . "\\" . Helpers::snakeToCamelCase($dataSlug) . "Generator";
        if(class_exists($className)){
            return new $className($args);
        }

        return null;
    }
}