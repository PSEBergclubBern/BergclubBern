<?php

namespace BergclubPlugin\Tests\Export\Data;

use BergclubPlugin\Export\Data\ContributionsGenerator;
use BergclubPlugin\Tests\Export\DataSeeder\AddressDataSeeder;
use BergclubPlugin\Tests\Mocks\UserMock;
use PHPUnit\Framework\TestCase;

class ContributionsGeneratorTest extends TestCase
{
    /**
     * @var ContributionsGenerator
     */
    public static $generator;

    /**
     * @var array
     */
    public static $expectedResult = [];

    public static function setUpBeforeClass()
    {
        AddressDataSeeder::seedContributions(UserMock::$findMitgliederWithoutSpouse, static::$expectedResult);

        static::$generator = new ContributionsGenerator();
        static::$generator->setUserClass("BergclubPlugin\\Tests\\Mocks\\UserMock");
        static::$generator->setOptionClass("BergclubPlugin\\Tests\\Mocks\\OptionMock");
    }

    /**
     * @test
     */
    public function getData()
    {
        $this->assertEquals(static::$expectedResult, static::$generator->getData());
    }
}