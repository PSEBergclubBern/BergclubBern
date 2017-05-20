<?php

namespace BergclubPlugin\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;

/**
 * Class Tour
 *
 * This metabox renders general information about the tour
 *
 * @package BergclubPlugin\Touren\MetaBoxes
 */
class Tour extends MetaBox
{

    const TYPE = '_type';
    const REQUIREMENTS_TECHNICAL = '_requirementsTechnical';
    const REQUIREMENTS_CONDITIONAL = '_requirementsConditional';
    const RISE_UP_METERS = '_riseUpMeters';
    const RISE_DOWN_METERS = '_riseDownMeters';
    const DISTANCE = '_distance';
    const DURATION = '_duration';
    const ADDITIONAL_INFO = '_additionalInfo';
    const TRAINING = '_training';
    const JSEVENT = '_jsEvent';
    const PROGRAM = '_program';
    const EQUIPMENT = '_equipment';
    const MAPMATERIAL = '_mapMaterial';
    const ONLINEMAP = '_onlineMap';
    const COSTS = '_costs';
    const COSTS_FOR = '_costsFor';


    public function getUniqueFieldNames()
    {
        return array(
            self::TYPE,
            self::REQUIREMENTS_TECHNICAL,
            self::REQUIREMENTS_CONDITIONAL,
            self::RISE_UP_METERS,
            self::RISE_DOWN_METERS,
            self::DISTANCE,
            self::DURATION,
            self::ADDITIONAL_INFO,
            self::TRAINING,
            self::JSEVENT,
            self::PROGRAM,
            self::EQUIPMENT,
            self::MAPMATERIAL,
            self::ONLINEMAP,
            self::COSTS,
            self::COSTS_FOR
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

    /**
     * Checks whether or not the Tour is valid or not. That means, are all required input fields not empty, and do the values make sense.
     * In case a tour cannot be validated a message is shown.
     *
     * @param $values - this are the values of the Tour itself
     * @param $posttype - gives the current step in the lifecycle of the post. When "draft" no validation happens.
     * @return bool
     */

    public function isValid($values, $posttype)
    {
        $errors = array();

        if ($posttype != "draft") {
            if (array_key_exists(self::DURATION, $values) && empty($values[self::DURATION])) {
                $errors[] = '"Dauer" darf nicht leer sein';
            }

            if (array_key_exists(self::ONLINEMAP, $values) && !empty($values[self::ONLINEMAP])) {
                if (!filter_var($values[self::ONLINEMAP], FILTER_VALIDATE_URL) && !filter_var("http://" . $values[self::ONLINEMAP], FILTER_VALIDATE_URL)) {
                    $errors[] = '"URL Online Route" muss eine gültige URL sein';
                }
            }

            if (array_key_exists(self::COSTS, $values)) {
                // replace all commas with dots
                $values[self::COSTS] = str_replace(',', '.', $values[self::COSTS]);
                if (!is_numeric($values[self::COSTS])) {
                    $errors[] = '"Kosten CHF" muss im Format 0.00 angegeben werden.';
                }
            }

            if (array_key_exists(self::RISE_UP_METERS, $values) && empty($values[self::RISE_UP_METERS])) {
                $errors[] = '"Aufstieg Höhenmeter" darf nicht leer sein';
            }

            if (array_key_exists(self::RISE_DOWN_METERS, $values) && empty($values[self::RISE_DOWN_METERS])) {
                $errors[] = '"Abstieg Höhenmeter" darf nicht leer sein';
            }

            if (array_key_exists(self::DISTANCE, $values) && empty($values[self::DISTANCE])) {
                $errors[] = '"Distanz (km)" darf nicht leer sein';
            }

            if (array_key_exists(self::EQUIPMENT, $values) && empty($values[self::EQUIPMENT])) {
                $errors[] = '"Ausrüstung" darf nicht leer sein';
            }

            if (array_key_exists(self::PROGRAM, $values) && empty($values[self::PROGRAM])) {
                $errors[] = '"Programm" darf nicht leer sein';
            }

        }
        foreach ($errors as $errorMsg) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
        }

        return count($errors) == 0;
    }

    protected function addAdditionalValuesForView()
    {
        $difficulty = array();
        foreach (get_option('bcb_tourenarten') as $key => $tourenart) {
            $difficulty[$key]['options'] = get_option($key);
            $difficulty[$key]['title'] = $tourenart;
        }

        return array(
            'tourenarten' => get_option('bcb_tourenarten'),
            'conditions' => array(1 => 'Leicht', 2 => 'Mittel', 3 => 'Schwer'),
            'difficulties' => $difficulty,
        );
    }

    protected function preSave($values)
    {
        $values = parent::preSave($values);

        if (array_key_exists(self::COSTS, $values) && !empty($values[self::COSTS])) {
            // replace all commas with dots
            $values[self::COSTS] = str_replace(',', '.', $values[self::COSTS]);
            if (is_numeric($values[self::COSTS])) {
                $values[self::COSTS] = number_format($values[self::COSTS], 2, '.', '');
            }
        }

        return $values;
    }

}