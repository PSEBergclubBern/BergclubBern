<?php

namespace BergclubPlugin\MVC\Models;


interface IUser
{
    /**
     * Returns the User object for the currently signed in user.
     *
     * @return IUser|null returns User object if found, null otherwise.
     */
    public static function findCurrent();

    /**
     * Finds and WP User with the given id and converts it to the User implementation.
     *
     * @param integer $id the user id
     * @return IUser|null returns the User object if found and if a address type role is assigned, null otherwise
     */
    public static function find($id, $allowWpUsers = false);

    /**
     * @return array An array with all User implementation objects except for spouses that are not set as main address.
     */
    public static function findAllWithoutSpouse();

    /**
     * Finds a User by the given WP login.
     *
     * @param string $login The username of the user to find.
     * @return IUser|null The user with the given username, null if not found.
     */
    public static function findByLogin($login);

    /**
     * Finds User which are members and which do not have a spouse or which have a spouse and are marked as main entry.
     *
     * @return array An array with all User objects that match the constraints.
     */
    public static function findMitgliederWithoutSpouse();

    /**
     * Finds User which are members.
     *
     * @return array An array with all User objects that match the constraints.
     */
    public static function findMitglieder();

    /**
     * Finds Users by the given roles.
     *
     * @param array $roleList a list of role slugs
     * @return array An array with User objects that have at least one of the given roles assigned.
     */
    public static function findByRoles(array $roleList);

    /**
     * Finds User by the given role.
     *
     * @param String $role A role slug.
     * @return array An array with User objects that have the given role assigned.
     */
    public static function findByRole($role);
}