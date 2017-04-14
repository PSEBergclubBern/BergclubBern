<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 22.03.17
 * Time: 08:29
 */

namespace BergclubPlugin\Tests\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\Touren\MetaBoxes\Tour;
use PHPUnit\Framework\TestCase;
use BergclubPlugin\Touren\MetaBoxes\Common;

class TourTest extends TestCase
{
    /**
     * @var Common
     */
    private $tour;

    /**
     * @Before
     */
    public function setUp() {
        $this->tour = new Tour();
    }

    /**
     * @test
     */
    public function hasStringAsIdentifier() {
        $this->assertNotEmpty($this->tour->getUniqueMetaBoxName());
        $this->assertTrue(is_string($this->tour->getUniqueMetaBoxName()));
    }

    /**
     * @test
     */
    public function hasStringAsTitle() {
        $this->assertNotEmpty($this->tour->getUniqueMetaBoxTitle());
        $this->assertTrue(is_string($this->tour->getUniqueMetaBoxTitle()));
    }

    /**
     * @test
     */
    public function fieldsReturnFieldAsArray() {
        $this->assertNotEmpty($this->tour->getUniqueFieldNames());
        $this->assertTrue(is_array($this->tour->getUniqueFieldNames()));
    }

    /**
     * @test
     */
    public function durationIsNoValidTime() {
        $this->assertFalse($this->tour->isValid(array(Tour::DURATION => 'test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function durationIsNoValidTime1() {
        $this->assertFalse($this->tour->isValid(array(Tour::DURATION => '1 Stunde; 15 Minuten'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function durationIsValidTime() {
        $this->assertTrue($this->tour->isValid(array(Tour::DURATION => '15:32'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function durationIsValidTime1() {
        $this->assertTrue($this->tour->isValid(array(Tour::DURATION => '5:32'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function durationIsValidTime2() {
        $this->assertTrue($this->tour->isValid(array(Tour::DURATION => '0:00'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function urlWithHttpsIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => 'https://www.swisstopo.com'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function urlIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => 'www.swisstopo.ch'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function urlWithSuffixIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => 'www.swisstopo.com/route1'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function urlWithSuffixAndParameterIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => 'www.swisstopo.com/route1?test=1'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function urlWithoutWWWIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => 'swisstopo.ch'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function kostenIsInvalid() {
        $this->assertFalse($this->tour->isValid(array(Tour::COSTS => 'test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function kostenWithDotIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::COSTS => '1.10'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function kostenWithCommaIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::COSTS => '1,10'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function kostenWithLargeAmountIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::COSTS => '100000,10'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function kostenWithoutFractionIsValid() {
        $this->assertTrue($this->tour->isValid(array(Tour::COSTS => '1'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function riseUpIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::RISE_UP_METERS => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::RISE_UP_METERS => ''), "publish"));
    }

    /**
     * @test
     */
    public function riseDownIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::RISE_DOWN_METERS => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::RISE_DOWN_METERS => ''), "publish"));
    }

    /**
     * @test
     */
    public function durationIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::DURATION => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::DURATION => ''), "publish"));
    }

    /**
     * @test
     */
    public function programIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::PROGRAM => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::PROGRAM => ''), "publish"));
    }

    /**
     * @test
     */
    public function equipmentIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::EQUIPMENT => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::EQUIPMENT => ''), "publish"));
    }

    /**
     * @test
     */
    public function costsIsOptionalForDraftButNotForPublish() {
        $this->assertTrue($this->tour->isValid(array(Tour::COSTS => ''), "draft"));
        $this->assertFalse($this->tour->isValid(array(Tour::COSTS => ''), "publish"));
    }

    /**
     * @test
     */
    public function onlineMapIsOptional() {
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => ''), "draft"));
        $this->assertTrue($this->tour->isValid(array(Tour::ONLINEMAP => ''), "publish"));
    }
}