<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 22.03.17
 * Time: 08:29
 */

namespace BergclubPlugin\Tests\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use PHPUnit\Framework\TestCase;
use BergclubPlugin\Touren\MetaBoxes\Common;

class CommonTest extends TestCase
{
    /**
     * @var Common
     */
    private $common;

    /**
     * @Before
     */
    public function setUp() {
        $this->common = new Common();
    }

    /**
     * @test
     */
    public function hasStringAsIdentifier() {
        $this->assertNotEmpty($this->common->getUniqueMetaBoxName());
        $this->assertTrue(is_string($this->common->getUniqueMetaBoxName()));
    }

    /**
     * @test
     */
    public function hasStringAsTitle() {
        $this->assertNotEmpty($this->common->getUniqueMetaBoxTitle());
        $this->assertTrue(is_string($this->common->getUniqueMetaBoxTitle()));
    }

    /**
     * @test
     */
    public function fieldsReturnFieldAsArray() {
        $this->assertNotEmpty($this->common->getUniqueFieldNames());
        $this->assertTrue(is_array($this->common->getUniqueFieldNames()));
    }

    /**
     * @test
     */
    public function fieldDateIsNotAcceptingInvalidDate() {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => 'test')));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function fieldDateIsAcceptingValidDates() {
        $this->assertTrue($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000')));
        $this->assertEmpty(FlashMessage::show());
    }
}