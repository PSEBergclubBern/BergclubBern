<?php

namespace BergclubPlugin\MVC\Models;

/**
 * Should be implemented by Models which have a single representation.
 *
 * @package BergclubPlugin\MVC\Models
 */
interface IModelSingle
{
    /**
     * Deletes the model with the given id from the database
     * @param $id
     * @return void
     */
    public static function remove($id);

    /**
     * Loads the model with the given id from the database and returns it if found
     * @param $id
     * @return IModelSingle|null
     */
    public static function find($id);

    /**
     * Saves the model object to database
     * @return void
     */
    public function save();

    /**
     * Deletes the model from database and resets its properties.
     * @return void
     */
    public function delete();
}