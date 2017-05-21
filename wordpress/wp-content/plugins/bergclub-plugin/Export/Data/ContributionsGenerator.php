<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Injectors\OptionClassInjector;
use BergclubPlugin\MVC\Injectors\UserClassInjector;
use BergclubPlugin\MVC\Models\IUser;
use BergclubPlugin\MVC\Models\User;

/**
 * Used for generating a list of members which have to pay membership fee including the additional information about
 * membership type and amount of fee.
 *
 * @package BergclubPlugin\Export\Data
 */
class ContributionsGenerator extends AbstractAddressLineGenerator
{
    use UserClassInjector;
    use OptionClassInjector;

    /**
     * Creates and returns an array with all users that have to pay a membership fee. If the user has a spouse, the user
     * will only be added to the array if marked as main entry. The spouse information will be added in
     * `AbstractAddressLineGenerator::addRow`
     *
     * @see AbstractAddressLineGenerator::getUsers()
     * @see AbstractAddressLineGenerator::addRow()
     * @see User::findMitgliederWithoutSpouse()
     *
     * @return array as described in method comment.
     */
    protected function getUsers()
    {
        $users = call_user_func($this->getUserClass() . '::findMitgliederWithoutSpouse');
        foreach ($users as $key => $user) {
            /* @var User $user */

            // if the user is not an "Aktivmitglied" or has a functionary role he does not have to pay a membership fee
            if (!strstr($user->address_role->getKey(), "aktivmitglied") || $user->hasFunctionaryRole()) {
                unset($users[$key]);
            } else {

                /* @var User $spouse */
                $spouse = $user->spouse;

                if (!is_null($spouse)) {
                    // if the users spouse has a functionary role, the user does not have to pay a membership fee
                    if ($spouse->hasFunctionaryRole()) {
                        unset($users[$key]);
                    }
                }
            }
        }

        return $users;
    }

    /**
     * Adds membership type and payment fee to the given address line
     *
     * @see AbstractAddressLineGenerator::addRow()
     *
     * @param array $row the generated address line for the given user
     * @param User $user the user associated with the address line
     */
    protected function addAdditionalData(array &$row, IUser $user)
    {
        $contributions = call_user_func($this->getOptionClass() . '::get', 'mitgliederbeitraege');
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