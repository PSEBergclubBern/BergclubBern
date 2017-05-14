<?php

namespace BergclubPlugin\Export\Data;


interface Generator
{
    /**
     * @return array
     */
    public function getData();
}