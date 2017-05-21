<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 22:23
 */

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Injectors\UserClassInjector;
use BergclubPlugin\MVC\Models\User;

/**
 * Generates the data needed for creating a member list.
 *
 * @see AbstractGenerator
 * @package BergclubPlugin\Export\Data
 */
class MembersGenerator extends AbstractGenerator
{

    use UserClassInjector;

    /**
     * Generates and returns an array which holds rows with member information.
     * <p>
     * Example:
     * <code>
     * [
     *   ['Type' => 'Aktivmitglied'],
     *   ['Anrede' => 'Herr'],
     *   ['Nachname' => 'Muster'],
     *   ['Vorname' => 'Fritz'],
     *   ['Zusatz'] => 'Postfach'],
     *   ['Strasse'] => 'Musterstrasse'],
     *   ['PLZ'] => '9999',
     *   ['Ort'] => 'Musterlingen',
     *   ['Telefon (P)'] => '031 234 56 78',
     *   ['Telefon (G)'] => '031 901 23 45',
     *   ['Telefon (M)'] => '079 678 90 01',
     *   ['Email'] => 'fritz@muster.com',
     *   ['Geburtsdatum'] => '01.02.1983',
     *   ['Ehepartner'] => 'Sabine Muster',
     *   ['Funktionen'] => 'Präsident, Leiter',
     * ],
     * [...]
     * </code>
     * <p>
     * Note:
     * - Every row always have the same amount of entries with the same keys. The value of an entry can be null.
     * - The entry with the key 'Funktionen' contains a list with comma separated functionary roles of the member (if
     *   user has functionary roles, null otherwise)
     *
     * @return array a list of members as described in the class comment.
     */
    public function getData()
    {
        $data = [];
        $users = call_user_func($this->getUserClass() . '::findMitglieder');
        foreach ($users as $user) {
            /* @var User $user */
            $row['Typ'] = $user->address_role_name;
            $row['Anrede'] = $user->gender;
            $row['Nachname'] = $user->last_name;
            $row['Vorname'] = $user->first_name;
            $row['Zusatz'] = $user->address_addition;
            $row['Strasse'] = $user->street;
            $row['PLZ'] = $user->zip;
            $row['Ort'] = $user->location;
            $row['Telefon (P)'] = $user->phone_private;
            $row['Telefon (G)'] = $user->phone_work;
            $row['Telefon (M)'] = $user->phone_mobile;
            $row['Email'] = $user->email;
            $row['Geburtsdatum'] = $user->birthdate;
            $row['Ehepartner'] = null;
            $row['Funktionen'] = null;

            /* @var User $spouse */
            $spouse = $user->spouse;
            if ($spouse) {
                $row["Ehepartner"] = $spouse->last_name . ' ' . $spouse->first_name;
            }

            $roles = $user->functionary_roles;
            $arr = [];
            foreach ($roles as $role) {
                /* @var \BergclubPlugin\MVC\Models\Role $role */
                $arr[] = $role->getName();
            }

            if (!empty($arr)) {
                $row['Funktionen'] = join(', ', $arr);
            }
            $data[] = $row;
        }

        return $data;
    }
}