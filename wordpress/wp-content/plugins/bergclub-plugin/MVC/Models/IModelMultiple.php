<?php

namespace BergclubPlugin\MVC\Models;

/**
 * Should be implemented in Models which can have multiple representations.
 */
interface IModelMultiple extends IModelSingle
{
    /**
     * Loads all models from the database.
     * @return array
     */
    public static function findAll();
}