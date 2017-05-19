<?php

namespace BergclubPlugin\Export\Format;

/**
 * Holds optional arguments array given to the constructor.
 *
 * @package BergclubPlugin\Export\Format
 */
abstract class AbstractFormat implements Format
{
    /**
     * @var array $args ;
     */
    protected $args;

    /**
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        $this->args = $args;
    }
}