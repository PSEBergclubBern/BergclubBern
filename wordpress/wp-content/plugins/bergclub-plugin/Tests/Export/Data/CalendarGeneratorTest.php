<?php

namespace{
    require_once __DIR__ . '/../../wp_bcb_functions_mocks.php';
}

namespace BergclubPlugin\Tests\Export\Data {

    use BergclubPlugin\Export\Data\CalendarGenerator;
    use BergclubPlugin\Tests\Export\DataSeeder\TourenDataSeeder;
    use PHPUnit\Framework\TestCase;

    class CalendarGeneratorTest extends TestCase
    {
        /**
         * @var CalendarGenerator
         */
        public static $generator;

        /**
         * @var array
         */
        public static $expectedResult = [];

        public static function setUpBeforeClass()
        {
            global $mockedPostData;
            TourenDataSeeder::seedCalendar($mockedPostData, static::$expectedResult);

            static::$generator = new CalendarGenerator();
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