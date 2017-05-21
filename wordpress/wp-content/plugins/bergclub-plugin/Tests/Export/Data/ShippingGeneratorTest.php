<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\ShippingGenerator;
use BergclubPlugin\Tests\Export\DataSeeder\AddressDataSeeder;
use BergclubPlugin\Tests\Mocks\UserMock;
use PHPUnit\Framework\TestCase;

class ShippingGeneratorTest extends TestCase
{
    /**
     * @var ShippingGenerator
     */
    public static $generator;

    /**
     * @var array
     */
    public static $expectedResult = [];

    public static function setUpBeforeClass(){
        AddressDataSeeder::seedShipping(UserMock::$findAllWithoutSpouse, static::$expectedResult);

        static::$generator = new ShippingGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Mocks\\UserMock");
    }

    /**
     * @test
     */
    public function getData(){
        $this->assertEquals(static::$expectedResult, static::$generator->getData());
    }

    /**
     * @test
     */
    public function ensureThatResultIsEmptyIfNoUserIsMarkedForShipment(){
        AddressDataSeeder::seedAddresses(UserMock::$findAllWithoutSpouse, static::$expectedResult);
        $this->assertEmpty(static::$generator->getData());
    }
}