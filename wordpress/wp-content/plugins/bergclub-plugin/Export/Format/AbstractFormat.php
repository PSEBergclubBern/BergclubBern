<?php

namespace BergclubPlugin\Export\Format;


abstract class AbstractFormat implements Format
{
    /**
     * @var array $args ;
     */
    protected $args;

    public function __construct(array $args = [])
    {
        $this->args = $args;
    }
}