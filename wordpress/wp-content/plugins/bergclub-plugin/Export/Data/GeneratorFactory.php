<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\Export\Factory;
use BergclubPlugin\MVC\Helpers;

/**
 * Factory for data generators.
 *
 * @package BergclubPlugin\Export\Data
 */
class GeneratorFactory implements Factory
{
    /**
     * Searches for a class based on a given slug that is in the same namespace as this class and implements `Generator`.
     * The slug will be transformed to class name spelling and the word "Generator" will be added.
     * <p>
     * Example: If the slug is "xls" it will look for a class named "XlsGenerator" in the same namespace as this class
     * is and check that the `Generator` interface is implemented in this class.
     *
     * @see Generator
     *
     * @param string $dataSlug the slug for which the factory will lookout for the needed class.
     * @param array $args arguments that will be passed to the newly generated instance.
     * @return null|Generator the concrete object or null if not found or `Generator` interface is not implemented.
     */
    public static function getConcrete($dataSlug, array $args = [])
    {
        $className = __NAMESPACE__ . "\\" . Helpers::snakeToCamelCase($dataSlug) . "Generator";
        if (class_exists($className) && isset(class_implements($className)[__NAMESPACE__ . "\\Generator"])) {
            return new $className($args);
        }

        return null;
    }
}