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
    const MEETPOINT_DIFFERENT = '_meetpoint-different';
    const TIME = '_time';
    const RETURNBACK = '_returnback';


/*
- Kartenmaterial (Textfeld, single line)
- URL Online Route (Textfeld, single line, z.B. schweizmobilplus.ch)
- Verpflegung (Textfeld, multi line)
- Übernachtung (Textfeld, multi line, wird nur angezeigt wenn Datum
bis definiert und nicht gleich Datum von)
- Kosten CHF (Textfeld, single line, Betrag #(#*).##)
- Kosten für (Textfeld, single line)
- Anmeldung bis (Datepicker)
- Anmeldung an (Dropdown Leiter / Co-Leiter)
 */
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