<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\ShippingGenerator;
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
        UserDataSeeder::seedShipping(UserMock::$findAllWithoutSpouse, static::$expectedResult);

        static::$generator = new ShippingGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Export\\Data\\UserMock");
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
        UserDataSeeder::seedAddresses(UserMock::$findAllWithoutSpouse, static::$expectedResult);
        $this->assertEmpty(static::$generator->getData());
    }
}