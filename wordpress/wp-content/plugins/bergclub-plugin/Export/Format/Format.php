<?php

namespace BergclubPlugin\Export\Format;


use BergclubPlugin\Export\Data\Generator;

interface Format
{
    public function output(Generator $dataGenerator, $name);
}