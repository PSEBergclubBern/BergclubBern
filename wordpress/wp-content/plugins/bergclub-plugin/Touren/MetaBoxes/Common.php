<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


class Common extends MetaBox
{
    protected function getUniqueFieldNames()
    {
        return array(
            '_date-from' => 'dateFrom',
        );
    }

    protected function getUniqueMetaBoxName()
    {
        return 'common-metadata';
    }

    protected function getUniqueMetaBoxTitle()
    {
        return 'Zusatzinformationen';
    }

}