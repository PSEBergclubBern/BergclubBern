<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:39
 */

namespace Commands;


abstract class Init
{
    protected function convertTextField($text)
    {
        // urldecode
        $text = urldecode($text);

        // to utf8
        $text = iconv('iso-8859-1', 'UTF-8', $text);

        // replace br
        $text = str_replace('<br>', '', $text);

        return $text;
    }
}