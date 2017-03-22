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
    const DATE_FROM_IDENTIFIER = '_date-from';

    public function getUniqueFieldNames()
    {
        return array(
            self::DATE_FROM_IDENTIFIER,
        );
    }

    public function getUniqueMetaBoxName()
    {
        return 'common-metadata';
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