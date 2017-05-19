<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

/**
 * Used for generating address list for "Quartalsprogramm" post mail.
 * @package BergclubPlugin\Export\Data
 */
class ShippingGenerator extends AbstractAddressLineGenerator
{

    /**
     * Creates and returns an array with all users that have program shipment activated. Only users which have no spouse
     * or users that have a spouse and are marked as main address entry will be added to the list. The spouse information
     * will be added in `AbstractAddressLineGenerator::addRow`
     *
     * @see AbstractAddressLineGenerator::getUsers()
     * @see AbstractAddressLineGenerator::addRow()
     * @see User::findAllWithoutSpouse()
     *
     * @return array as described in method comment.
     */
    protected function getUsers()
    {
        $users = User::findAllWithoutSpouse();
        foreach ($users as $key => $user) {
            /* @var User $user */
            if (!$user->raw_program_shipment) {
                unset($users[$key]);
            }
        }

        return $users;
    }

    /**
     * For the program shipping address list no additional info is needed.
     *
     * @param array $row
     * @param User $user
     */
    protected function addAdditionalData(array &$row, User $user)
    {

    }
}