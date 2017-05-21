<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\AddressesGenerator;
use BergclubPlugin\Tests\Export\DataSeeder\AddressDataSeeder;
use BergclubPlugin\Tests\Mocks\UserMock;
use PHPUnit\Framework\TestCase;

class AddressesGeneratorTest extends TestCase
{
    /**
     * @var AddressesGenerator
     */
    public static $generator;

    /**
     * @var array
     */
    public static $expectedResult = [];

    public static function setUpBeforeClass()
    {
        AddressDataSeeder::seedAddresses(UserMock::$findAllWithoutSpouse, static::$expectedResult);

        static::$generator = new AddressesGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Mocks\\UserMock");
    }

    /**
     * @test
     */
    public function getData()
    {
        $this->assertEquals(static::$expectedResult, static::$generator->getData());
    }
}