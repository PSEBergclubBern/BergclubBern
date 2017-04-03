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
    /**
     * Convert a title field for the new database
     *
     * @param $text
     * @return mixed|string
     */
    protected function convertTitleField($text){
        return $this->cleanUp($text);
    }

    /**
     * Convert a text field for the new database
     *
     * @param $text
     * @return mixed|string
     */
    protected function convertTextField($text)
    {
        $text = $this->convertEncoding($text);
        return $this->cleanUp($text);
    }

    /**
     * cleanup old field
     *
     * @param $text
     * @return mixed|string
     */
    private function cleanUp($text){
        // replace br
        $text = str_replace('<br>', '', $text);

        // remove leading and trailing whitespaces
        $text = trim($text);

        return $text;
    }

    /**
     * convert encoding to new format
     *
     * @param $text
     * @return string
     */
    private function convertEncoding($text){
        // urldecode
        $text = urldecode($text);

        // to utf8
        $text = iconv('iso-8859-1', 'UTF-8', $text);

        return $text;
    }
}