<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class MeetingPoint extends MetaBox
{
    const MEETPOINT = '_meetpoint';
    const MEETPOINT_DIFFERENT = '_meetpointDifferent';
    const TIME = '_meetingPointTime';
    const RETURNBACK = '_returnBack';
    const FOOD = '_food';

    public function getUniqueFieldNames()
    {
        return array(
            self::MEETPOINT,
            self::MEETPOINT_DIFFERENT,
            self::TIME,
            self::RETURNBACK,
            self::FOOD,
        );
    }

    protected function addAdditionalValuesForView()
    {
        return array(
            'meetingPoints' => array(
                array('id' => 1, 'text' => 'Bern HB, Treffpunkt'),
                array('id' => 2, 'text' => 'Bern HB, auf dem Abfahrtsperron'),
                array('id' => 3, 'text' => 'Bern, auf der Welle'),
                array('id' => 0, 'text' => 'Anderer'),
            ),
        );
    }

    public function getUniqueMetaBoxName()
    {
        return 'meetingpoint';
    }

    public function getUniqueMetaBoxTitle()
    {
        return 'Treffpunkt';
    }

    public function isValid($values)
    {
        $errors = array();

        foreach ($errors as $errorMsg) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
        }
        return count($errors) == 0;
    }

}