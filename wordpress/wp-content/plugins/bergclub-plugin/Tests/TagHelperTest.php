<?php

namespace {
    function globalFunction()
    {
        return "global function";
    }
}

namespace BergclubPlugin\Tests {

    use BergclubPlugin\TagHelper;
    use PHPUnit\Framework\TestCase;


    class HelperObject
    {

        public static function staticMethod()
        {
            return "static method";
        }

        public function nonStaticMethod()
        {
            return "non-static method";
        }
    }

    class TagHelperTest extends TestCase
    {


        /**
         * @test
         */
        public function globalFunction()
        {
            TagHelper::addTag('global-function', "globalFunction");
            $this->assertEquals('global function', TagHelper::getTag('global-function'));
        }

        /**
         * @test
         */
        public function staticMethod()
        {
            TagHelper::addTag('static-method', ["BergclubPlugin\\Tests\\HelperObject", "staticMethod"]);
            $this->assertEquals('static method', TagHelper::getTag('static-method'));
        }

        /**
         * @test
         */
        public function nonStaticMethod()
        {
            TagHelper::addTag('non-static-method', [new HelperObject(), "nonStaticMethod"]);
            $this->assertEquals('non-static method', TagHelper::getTag('non-static-method'));
        }

        /**
         * @test
         */
        public function getKeys()
        {
            TagHelper::addTag('global-function', "globalFunction");
            TagHelper::addTag('static-method', ["BergclubPlugin\\Tests\\HelperObject", "staticMethod"]);
            TagHelper::addTag('non-static-method', [new HelperObject(), "nonStaticMethod"]);

            $this->assertEquals(['global-function', 'static-method', 'non-static-method'], TagHelper::getKeys());
        }
    }
}
