<?php

namespace BergclubPlugin\Export\Data;

/**
 * Interface Generator
 * @package BergclubPlugin\Export\Data
 */
interface Generator
{
    /**
     * Needs to return an array with data used for generating output.
     * The format of the array depends on the desired output format.
     * For further information have a look at the classes which extend the `BergclubPlugin\Export\AbstractFormat` class.
     *
     * @see Format
     * @see AbstractFormat
     *
     * @return array
     */
    public function getData();
}