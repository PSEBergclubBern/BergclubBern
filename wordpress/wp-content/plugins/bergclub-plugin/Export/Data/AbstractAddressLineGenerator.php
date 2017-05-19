<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

/**
 * Holds an array of address lines which will be filled and returning when calling `getData`.
 * Every address line consists of an array with keys "Adresszeile 1", "Adresszeile 2", ...
 * Ensures that every address line array has the same keys if not all keys are used, the value will be null but the key
 * exists. Null values can only be at the end of the address line array not between to entries that have a value set.
 * Exception: Additionally data that is added to the address line with `addAdditionalData` which is used to add additional
 * entries to a address lines and holds other information than the address itself (e.g. membership fee).
 *
 * @package BergclubPlugin\Export\Data
 */
abstract class AbstractAddressLineGenerator extends AbstractGenerator
{
    protected $maxIndex = 0;
    protected $data = [];

    /**
     * Fills and returns the address lines array described in the classe comment.
     * <p>
     * Note: When overriding this method, it must be ensured that the constraints described in the class comment
     * holds.
     *
     * @return array The address line array described in the class comment.
     */
    public function getData()
    {
        $data = [];
        $users = [];

        $users = $this->getUsers();

        foreach ($users as $user) {
            $this->addRow($user);
        }

        foreach ($data as &$row) {
            for ($i = 1; $i <= $this->maxIndex; $i++) {
                if (!isset($row["Adresszeile " . $i])) {
                    $row["Adresszeile " . $i] = null;
                }
            }
        }

        return $this->data;
    }

    /**
     * Must return an array with the user objects for which the address lines should be generated.
     *
     * @return array User objects for which the address lines should be generated
     * @see AbstractAddressLineGenerator::getData()
     */
    abstract protected function getUsers();

    /**
     * Creates an address line as described in the class comment from the given user and adds the line to the existing
     * address lines.
     *
     * There are different possibilities how an address line is filled.
     * - company name is set, but not first name and last name:
     *   ["Adresszeile 1"] => company,
     *
     * - company name is set and first/last name also:
     *   ["Adresszeile 1"] => company,
     *   ["Adresszeile 2"] => salutation first_name last_name,
     *
     * - company name is not set and given user has no spouse:
     *   ["Adresszeile 1"] => salutation,
     *   ["Adresszeile 2"] => first_name last_name
     *
     * - company name is not set and given user has a spouse with same lastname but different gender:
     *   ["Adresszeile 1"] => salutation_User & salutation_Spouse
     *   ["Adresszeile 2"] => first_name_User & first_name_Spouse last_name_User
     *
     * - company name is not set and given user has a spouse with same lastname and gender:
     *   ["Adresszeile 1"] => salutation_User . "en" ("Herren" or "Frauen")
     *   ["Adresszeile 2"] => first_name_User & first_name_Spouse last_name_User
     *
     * - company name is not set and given user has a spouse with different lastname:
     *   ["Adresszeile 1"] => salutation_User first_name_User last_name_User
     *   ["Adresszeile 2"] => salutation_Spouse first_name_Spouse last_name_Spouse
     *
     * - the further entries will be filled as follows. The entry only is generated if the current field has a value,
     *   otherwise, it will be ignored. At first n is the last "Adresszeile" enumeration + 1. If the entry is generated
     *   n becomes n+1:
     *   ["Adresszeile n"] = address_affix
     *   ["Adresszeile n"] = street
     *   ["Adresszeile n"] = zip location
     *
     * <p>
     * Note: When overriding this method, it must be ensured that the constraints described in the class comment
     * holds.
     *
     * `addAdditionalData` should be called for every generated row.
     *
     * @param User $user the user for which an address line array needs to be generated.
     * @see AbstractAddressLineGenerator::addAdditionalData()
     */
    protected function addRow(User $user)
    {
        $currentIndex = 0;
        $row = [];
        for ($i = 1; $i < 7; $i++) {
            $row["Adresszeile " . $i] = "";
        }
        $role = $user->address_role->getKey();
        if ($user->company) {
            $currentIndex++;
            $row["Adresszeile " . $currentIndex] = $user->company;
        }

        /* @var User $spouse */
        $spouse = $user->spouse;

        if (!empty(trim($user->first_name . $user->last_name))) {
            if (empty($spouse)) {
                if ($user->company) {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                } else {
                    if ($user->gender) {
                        $currentIndex++;
                        $row["Adresszeile " . $currentIndex] = $user->gender;
                    }
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->first_name . ' ' . $user->last_name);
                }
            } else {
                if ($user->last_name == $spouse->last_name) {
                    $gender = $user->gender;
                    if ($user->gender == $spouse->gender) {
                        $gender .= "en";
                    } else {
                        $gender .= " & " . $spouse->gender;
                    }
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $gender;
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = $user->first_name . ' & ' . $spouse->first_name . ' ' . $user->last_name;
                } else {
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($user->gender . ' ' . $user->first_name . ' ' . $user->last_name);
                    $currentIndex++;
                    $row["Adresszeile " . $currentIndex] = trim($spouse->gender . ' ' . $spouse->first_name . ' ' . $spouse->last_name);
                }
            }
        }

        if (!empty($user->address_addition)) {
            $currentIndex++;
            $row["Adresszeile " . $currentIndex] = $user->addressAddition;
        }

        $currentIndex++;
        $row["Adresszeile " . $currentIndex] = $user->street;

        $currentIndex++;
        $row["Adresszeile " . $currentIndex] = trim($user->zip . ' ' . $user->location);

        if ($currentIndex > $this->maxIndex) {
            $this->maxIndex = $currentIndex;
        }

        $this->addAdditionalData($row, $user);

        $this->data[] = $row;
    }

    /**
     * Can be used to add additional information to a generated address line.
     * Will be called from `addRow` method whenever a new address line is generated.
     *
     * @param array $row the address line to which additional data can be added
     * @param User $user the user related to the given address line
     *
     * @see AbstractAddressLineGenerator::addRow()
     */
    abstract protected function addAdditionalData(array &$row, User $user);
}