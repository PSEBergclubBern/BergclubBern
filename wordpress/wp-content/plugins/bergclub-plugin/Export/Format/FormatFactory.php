<?php

namespace BergclubPlugin\Export\Format;

use BergclubPlugin\Export\Factory;
use BergclubPlugin\MVC\Helpers;

/**
 * Factory for file download formats
 *
 * @package BergclubPlugin\Export\Format
 */
class FormatFactory implements Factory
{

    /**
     * Searches for a class based on a given slug that is in the same namespace as this class and implements `Format`.
     * The slug will be transformed to class name spelling and the word "Format" will be added.
     * <p>
     * Example: If the slug is "xls" it will look for a class named "XlsFormat" in the same namespace as this class is
     * and check that the `Format` interface is implemented in this class.
     *
     * @see Format
     *
     * @param string $formatSlug the slug for which the factory will lookout for the needed class.
     * @param array $args arguments that will be passed to the newly generated instance.
     * @return null|Format the concrete object or null if not found or `Format` interface is not implemented.
     */
    public static function getConcrete($formatSlug, array $args = [])
    {
        $className = __NAMESPACE__ . "\\" . Helpers::snakeToCamelCase($formatSlug) . "Format";
        if (class_exists($className) && isset(class_implements($className)[__NAMESPACE__ . "\\Format"])) {
            return new $className($args);
        }

        return null;
    }
}