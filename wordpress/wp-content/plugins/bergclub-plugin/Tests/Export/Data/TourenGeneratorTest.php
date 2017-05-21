<?php

namespace {
    require_once __DIR__ . '/../../wp_bcb_functions_mocks.php';
}

namespace BergclubPlugin\Tests\Export\Data {

    use BergclubPlugin\Export\Data\TourenGenerator;
    use BergclubPlugin\Tests\Export\DataSeeder\TourenDataSeeder;
    use PHPUnit\Framework\TestCase;

    class TourenGeneratorTest extends TestCase
    {
        /**
         * @var TourenGenerator
         */
        public static $generator;

        /**
         * @var array
         */
        public static $expectedResult = [];

        public static function setUpBeforeClass()
        {
            global $mockedPostData;
            TourenDataSeeder::seedTouren($mockedPostData, static::$expectedResult);

            static::$generator = new TourenGenerator(['status' => 'x', 'from' => 'y', 'to' => 'z']);
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