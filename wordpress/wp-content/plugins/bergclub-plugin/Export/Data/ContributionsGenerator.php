<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

/**
 * Used for generating a list of members which have to pay membership fee including the additional information about
 * membership type and amount of fee.
 *
 * @package BergclubPlugin\Export\Data
 */
class ContributionsGenerator extends AbstractAddressLineGenerator
{
    /**
     * Creates and returns an array with all users that have to pay a membership fee. If the user has a spouse, the user
     * will only be added to the array if marked as main entry. The spouse information will be added in
     * `AbstractAddressLineGenerator::addRow`
     *
     * @see AbstractAddressLineGenerator::getUsers()
     * @see AbstractAddressLineGenerator::addRow()
     *
     * @return array as described in method comment.
     */
    protected function getUsers()
    {
        $users = User::findMitgliederWithoutSpouse();
        foreach ($users as $key => $user) {
            /* @var User $user */
            if ($user->hasFunctionaryRole()) {
                unset($users[$key]);
            }

            /* @var User $spouse */
            $spouse = $user->spouse;

            if (!is_null($spouse)) {
                if ($spouse->hasFunctionaryRole()) {
                    unset($users[$key]);
                }
            }
        }

        return $users;
    }

    /**
     * Adds membership type and payment fee to the given address line
     *
     * @param array $row the generated address line for the given user
     * @param User $user the user associated with the address line
     *
     * @see AbstractAddressLineGenerator::addRow()
     */
    protected function addAdditionalData(&$row, User $user)
    {
        $contributions = Option::get('mitgliederbeitraege');
        $contributionType = $contributions['bcb']['name'];
        $contributionAmount = $contributions['bcb']['amount'];

        if (!is_null($user->spouse)) {
            $contributionType = $contributions['ehepaar']['name'];
            $contributionAmount = $contributions['ehepaar']['amount'];
        } elseif ($user->address_role->getKey() == 'bcb_aktivmitglied_jugend') {
            $contributionType = $contributions['jugend']['name'];
            $contributionAmount = $contributions['jugend']['amount'];
        }

        $row["Beitragstyp"] = $contributionType;
        $row["Betrag"] = number_format($contributionAmount, 2, '.', '');
    }
}