<?php

namespace{
    require_once __DIR__ . '/../../wp_bcb_functions_mocks.php';
}

namespace BergclubPlugin\Tests\Export\Data {

    use BergclubPlugin\Export\Data\PfarrblattGenerator;
    use BergclubPlugin\Tests\Export\DataSeeder\TourenDataSeeder;
    use PHPUnit\Framework\TestCase;

    class PfarrblattGeneratorTest extends TestCase
    {
        /**
         * @var PfarrblattGenerator
         */
        public static $generator;

        /**
         * @var array
         */
        public static $expectedResult = [];

        public static function setUpBeforeClass()
        {
            global $mockedPostData;
            TourenDataSeeder::seedPfarrblatt($mockedPostData, static::$expectedResult);

            static::$generator = new PfarrblattGenerator(['from' => 'x', 'to' => 'y']);
        }

        /**
         * @test
         */
        public function getData()
        {
            $this->assertEquals(static::$expectedResult, static::$generator->getData());
        }
    }
}