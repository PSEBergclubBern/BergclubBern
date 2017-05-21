<?php

namespace{
    require_once __DIR__ . '/../../wp_bcb_functions_mocks.php';
}

namespace BergclubPlugin\Tests\Export\Data {

    use BergclubPlugin\Export\Data\ProgramGenerator;
    use BergclubPlugin\Tests\Export\DataSeeder\TourenDataSeeder;
    use PHPUnit\Framework\TestCase;

    class ProgramGeneratorTest extends TestCase
    {
        /**
         * @var ProgramGenerator
         */
        public static $generator;

        /**
         * @var array
         */
        public static $expectedResult = [];

        public static function setUpBeforeClass()
        {
            global $mockedPostData;
            TourenDataSeeder::seedProgram($mockedPostData, static::$expectedResult);

            static::$generator = new ProgramGenerator(['touren-from' => 'x', 'touren-to' => 'y']);
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