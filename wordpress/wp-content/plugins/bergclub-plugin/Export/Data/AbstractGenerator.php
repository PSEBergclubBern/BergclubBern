<?php

namespace BergclubPlugin\Export\Data;


abstract class AbstractGenerator implements Generator
{
    /**
     * @var array $args;
     */
    protected $args;

    public function __construct(array $args = [])
    {
        $this->args = $args;
    }
}