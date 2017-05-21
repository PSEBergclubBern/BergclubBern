<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 17:14
 */

namespace BergclubPlugin\Tests\Mocks;


class RoleMock
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $name;

    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}