<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Common extends MetaBox
{
    const DATE_FROM_IDENTIFIER = '_dateFrom';
    const DATE_TO_IDENTIFIER = '_dateTo';
    const LEADER = '_leader';
    const CO_LEADER = '_coLeader';
    const SIGNUP_UNTIL = '_signupUntil';
    const SIGNUP_TO = '_signupTo';
    const SLEEPOVER = '_sleepOver';

    public function getUniqueFieldNames()
    {
        return array(
            self::DATE_FROM_IDENTIFIER,
            self::DATE_TO_IDENTIFIER,
            self::LEADER,
            self::CO_LEADER,
            self::SIGNUP_UNTIL,
            self::SIGNUP_TO,
            self::SLEEPOVER,
        );
    }

    protected function addAdditionalValuesForView()
    {
        return array(
            'leiter' => \get_users(array('role_in' => 'Leiter')),
        );
    }

    public function getUniqueMetaBoxName()
    {
        return 'common';
    }

    public function getUniqueMetaBoxTitle()
    {
        return 'Zusatzinformationen';
    }

    public function isValid($values)
    {
        $errors = array();
        if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values)) {
            $date = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_FROM_IDENTIFIER]);
            if ($date === false) {
                $errors[] = 'Datum ist ungültig';
            }
        }

        foreach ($errors as $errorMsg) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
        }
        return count($errors) == 0;
    }

}