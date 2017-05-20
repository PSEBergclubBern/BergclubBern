<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\AddressesGenerator;
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

    public static function setUpBeforeClass(){
        UserDataSeeder::seedAddresses(UserMock::$findAllWithoutSpouse, static::$expectedResult);

        static::$generator = new AddressesGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Export\\Data\\UserMock");
    }

    /**
     * @test
     */
    public function getData(){
        $this->assertEquals(static::$expectedResult, static::$generator->getData());
    }
}