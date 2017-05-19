<?php

namespace BergclubPlugin\Export\Data;

/**
 * Holds optional arguments array given to the constructor.
 *
 * @package BergclubPlugin\Export\Data
 */
abstract class AbstractGenerator implements Generator
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