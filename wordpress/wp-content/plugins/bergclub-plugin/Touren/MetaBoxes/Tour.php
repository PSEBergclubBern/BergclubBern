<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Tour extends MetaBox
{

    const TYPE = '_type';
    const REQUIREMENTS_TECHNICAL = '_requirements-technical';
    const REQUIREMENTS_CONDITIONAL = '_requirements-conditional';
    const RISE_UP_METERS = '_rise_up_meters';
    const RISE_DOWN_METERS = '_rise_down_meters';
    const PAUSE_TIME = '_pause';
    const ADDITIONAL_INFO = '_additional-info';
    const TRAINING = '_training';
    const JSEVENT = '_jsevent';
    const PROGRAM = '_program';
    const EQUIPMENT = '_equipment';
    const MAPMATERIAL = '_map-material';
    const ONLINEMAP = '_onlinemap';



    public function getUniqueFieldNames()
    {
        return array();
    }

    protected function addAdditionalValuesForView()
    {
        return array(
        );
    }

    public function getUniqueMetaBoxName()
    {
        return 'tour';
    }

    public function getUniqueMetaBoxTitle()
    {
        return 'Tourdaten';
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