<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\MembersGenerator;
use BergclubPlugin\Tests\Export\DataSeeder\AddressDataSeeder;
use BergclubPlugin\Tests\Mocks\UserMock;
use PHPUnit\Framework\TestCase;

class MembersGeneratorTest extends TestCase
{
    /**
     * @var MembersGenerator
     */
    public static $generator;

    /**
     * @var array
     */
    public static $expectedResult = [];

    public static function setUpBeforeClass(){
        AddressDataSeeder::seedMembers(UserMock::$findMitglieder, static::$expectedResult);

        static::$generator = new MembersGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Mocks\\UserMock");
    }

    /**
     * @test
     */
    public function getData(){
        $this->assertEquals(static::$expectedResult, static::$generator->getData());
    }
}