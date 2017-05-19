<?php

namespace BergclubPlugin\Export;

/**
 * Interface Factory
 *
 * @package BergclubPlugin\Export
 */
interface Factory
{
    /**
     * Any factory that implements this method should decide on the given type string which concrete object has to
     * returned.
     *
     * @param string $type type of object to be generated
     * @param array $args optional arguments (e.g. for passing to the constructor of the object that is instanciated)
     * @return Object an object based on the given type.
     */
    public static function getConcrete($type, array $args = []);
}