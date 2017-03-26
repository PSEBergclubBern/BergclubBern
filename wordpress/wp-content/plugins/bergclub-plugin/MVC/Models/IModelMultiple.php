<?php

namespace BergclubPlugin\MVC\Models;


interface IModelMultiple extends IModelSingle
{
    /**
     * Loads all models from the database.
     * @return array
     */
    public static function findAll();
}