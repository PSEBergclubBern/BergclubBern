<?php

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Injectors\UserClassInjector;
use BergclubPlugin\MVC\Models\IUser;
use BergclubPlugin\MVC\Models\User;

/**
 * Used for generating complete address list
 * @package BergclubPlugin\Export\Data
 */
class AddressesGenerator extends AbstractAddressLineGenerator
{
    use UserClassInjector;


    /**
     * Creates and returns an array with all users that have no spouse or users that have a spouse and are marked as
     * main address entry. The spouse information will be added in `AbstractAddressLineGenerator::addRow`
     *
     * @see AbstractAddressLineGenerator::getUsers()
     * @see AbstractAddressLineGenerator::addRow()
     * @see User::findAllWithoutSpouse()
     *
     * @return array as described in method comment.
     */
    protected function getUsers()
    {
        return call_user_func($this->getUserClass() . '::findAllWithoutSpouse');
    }

    /**
     * For the complete address list no additional info is needed.
     *
     * @param array $row
     * @param User $user
     */
    protected function addAdditionalData(array &$row, IUser $user)
    {

    }
}