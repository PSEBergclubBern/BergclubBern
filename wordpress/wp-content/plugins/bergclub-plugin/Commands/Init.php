<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 14.03.17
 * Time: 16:39
 */

namespace BergclubPlugin\Commands;


abstract class Init
{
    protected function convertTextField($text)
    {
        // urldecode
        $text = urldecode($text);

        // to utf8
        $text = mb_convert_encoding($text, 'UTF-8');

        // replace br
        $text = str_replace('<br>', '', $text);

        return $text;
    }
}