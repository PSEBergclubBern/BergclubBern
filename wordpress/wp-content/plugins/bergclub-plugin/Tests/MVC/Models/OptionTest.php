<?php
namespace {
    require_once __DIR__ . '/wp_mocks.php';
}

namespace BergclubPlugin\Tests\MVC\Models {

    use BergclubPlugin\MVC\Models\Option;
    use PHPUnit\Framework\TestCase;

    class OptionTest extends TestCase
    {
        /**
         * @Before
         */
        public function setUp()
        {
            global $wpOptions;
            $wpOptions = [];
        }

        /**
         * @test
         */
        public function setAndGet()
        {
            Option::set('test', 'A');
            $this->assertEquals('A', Option::get('test'));
        }

        /**
         * @test
         */
        public function saveAndFind()
        {
            $option = new Option('test', 'B');
            $option->save();
            $this->assertEquals($option, Option::find('test'));
        }

        /**
         * @test
         */
        public function delete()
        {
            $option = new Option('test', 'C');
            $option->save();
            $this->assertEquals($option, Option::find('test'));

            $option->delete();
            $this->assertNull(Option::find('test')->getValue());
        }

        /**
         * @test
         */
        public function remove()
        {
            $option = new Option('test', 'C');
            $option->save();

            $this->assertEquals($option, Option::find('test'));

            Option::remove('test');
            $this->assertNull(Option::find('test')->getValue());
        }


    }
}