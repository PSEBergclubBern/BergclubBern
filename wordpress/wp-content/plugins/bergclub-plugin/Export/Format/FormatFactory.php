<?php

namespace BergclubPlugin\Export\Format;

use BergclubPlugin\Export\Factory;
use BergclubPlugin\MVC\Helpers;

class FormatFactory implements Factory
{
    public static function getConcrete($formatSlug, array $args = [])
    {
        $className = __NAMESPACE__ . "\\" . Helpers::snakeToCamelCase($formatSlug) . "Format";
        if (class_exists($className)) {
            return new $className($args);
        }

        return null;
    }
}