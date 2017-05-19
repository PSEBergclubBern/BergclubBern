<?php

namespace BergclubPlugin\Export\Format;


use BergclubPlugin\Export\Data\Generator;

/**
 * Used for download formats.
 *
 * @package BergclubPlugin\Export\Format
 */
interface Format
{
    /**
     * Must create and start a file download with the data from the given data generator.
     *
     * @param Generator $dataGenerator the data generator
     * @param string $name should be used for the filename of the downloaded file. Adding current time to download
     * recommended:
     * <p>
     * Example:
     * <code>
     * $name . ' ' . date("Y-m-d_H-i-s") . '.pdf'
     * </code>
     *
     * @return void
     */
    public function output(Generator $dataGenerator, $name);
}