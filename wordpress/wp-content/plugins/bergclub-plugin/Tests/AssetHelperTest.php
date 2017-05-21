<?php

namespace {
    require_once __DIR__ . '/wp_bcb_functions_mocks.php';
}

namespace BergclubPlugin\Tests {

    use BergclubPlugin\AssetHelper;
    use PHPUnit\Framework\TestCase;

    class AssetHelperTest extends TestCase
    {

        public static function setUpBeforeClass()
        {
            AssetHelper::addAsset('test1', 'stylesheet1.css');
            AssetHelper::addAsset('test2', 'stylesheet1.css');
            AssetHelper::addAsset('test2', 'stylesheet2.css');
            AssetHelper::addAsset('test3', 'stylesheet3.css');
            AssetHelper::addAsset('test3', 'javascript1.js');
            AssetHelper::addAsset('test4', 'javascript2.js');
        }

        public function setUp()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $wpEnqueuedStyles = null;
            $wpEnqueuedScripts = null;
        }

        /**
         * @test
         */
        public function noStyleSheetAndJs()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $_GET['page'] = 'anotherpage';
            AssetHelper::registerAssets();
            $this->assertNull($wpEnqueuedStyles);
            $this->assertNull($wpEnqueuedScripts);
        }

        /**
         * @test
         */
        public function oneStyleSheetAndNoJs()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $_GET['page'] = 'test1';
            AssetHelper::registerAssets();
            $this->assertEquals(['test1-0' => 'stylesheet1.css'], $wpEnqueuedStyles);
            $this->assertNull($wpEnqueuedScripts);
        }

        /**
         * @test
         */
        public function twoStyleSheetAndNoJs()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $_GET['page'] = 'test2';
            AssetHelper::registerAssets();
            $this->assertEquals(['test2-0' => 'stylesheet1.css', 'test2-1' => 'stylesheet2.css'], $wpEnqueuedStyles);
            $this->assertNull($wpEnqueuedScripts);
        }

        /**
         * @test
         */
        public function oneStyleSheetAndOneJs()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $_GET['page'] = 'test3';
            AssetHelper::registerAssets();
            $this->assertEquals(['test3-0' => 'stylesheet3.css'], $wpEnqueuedStyles);
            $this->assertEquals(['test3-1' => 'javascript1.js'], $wpEnqueuedScripts);
        }

        /**
         * @test
         */
        public function noStyleSheetAndOneJs()
        {
            global $wpEnqueuedStyles;
            global $wpEnqueuedScripts;

            $_GET['page'] = 'test4';
            AssetHelper::registerAssets();
            $this->assertNull($wpEnqueuedStyles);
            $this->assertEquals(['test4-0' => 'javascript2.js'], $wpEnqueuedScripts);
        }
    }
}