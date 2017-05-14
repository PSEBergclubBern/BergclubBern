<?php

namespace BergclubPlugin\Export;

interface Factory
{
    public static function getConcrete($type, array $args = []);
}