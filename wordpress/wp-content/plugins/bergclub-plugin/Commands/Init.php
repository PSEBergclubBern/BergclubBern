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

    protected function convertTitleField($text){
        return $this->cleanUp($text);
    }

    protected function convertTextField($text)
    {
        $text = $this->convertEncoding($text);
        return $this->cleanUp($text);
    }

    private function cleanUp($text){
        // replace br
        $text = str_replace('<br>', '', $text);

        // remove leading and trailing whitespaces
        $text = trim($text);

        return $text;
    }

    private function convertEncoding($text){
        // urldecode
        $text = urldecode($text);

        // to utf8
        $text = iconv('iso-8859-1', 'UTF-8', $text);

        return $text;
    }
}