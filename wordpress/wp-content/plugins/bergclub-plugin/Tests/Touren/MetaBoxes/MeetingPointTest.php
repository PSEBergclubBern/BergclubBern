<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 22.03.17
 * Time: 08:29
 */

namespace BergclubPlugin\Tests\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\Touren\MetaBoxes\MeetingPoint;
use BergclubPlugin\Touren\MetaBoxes\Tour;
use PHPUnit\Framework\TestCase;
use BergclubPlugin\Touren\MetaBoxes\Common;

class MeetingPointTest extends TestCase
{
    /**
     * @var Common
     */
    private $meeting;

    /**
     * @Before
     */
    public function setUp() {
        $this->meeting = new MeetingPoint();
    }

    /**
     * @test
     */
    public function hasStringAsIdentifier() {
        $this->assertNotEmpty($this->meeting->getUniqueMetaBoxName());
        $this->assertTrue(is_string($this->meeting->getUniqueMetaBoxName()));
    }

    /**
     * @test
     */
    public function hasStringAsTitle() {
        $this->assertNotEmpty($this->meeting->getUniqueMetaBoxTitle());
        $this->assertTrue(is_string($this->meeting->getUniqueMetaBoxTitle()));
    }

    /**
     * @test
     */
    public function fieldsReturnFieldAsArray() {
        $this->assertNotEmpty($this->meeting->getUniqueFieldNames());
        $this->assertTrue(is_array($this->meeting->getUniqueFieldNames()));
    }

    /**
     * @test
     */
    public function treffpunktZeitIsNoValidTime() {
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::TIME => 'test')));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktZeitIsValidTime() {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::TIME => '15:32')));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function rückkehrIsNoValidTime() {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::RETURNBACK => 'test')));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function rückkehrIsValidTime() {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::RETURNBACK => '15:32')));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktAnderesNoAlternativTreffpunkt() {
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 'Anderes')));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktAnderesWithAlternativTreffpunkt() {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 'Anderes', MeetingPoint::MEETPOINT_DIFFERENT => 'Welle')));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktSet() {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 'Test')));
        $this->assertEmpty(FlashMessage::show());
    }

	/**
	 * @test
	 */
	public function alternativTreffpunktSetButNoTreffpunk() {
		$this->assertFalse($this->meeting->isValid(array(MeetingPoint::MEETPOINT_DIFFERENT => 'Test')));
		$this->assertNotEmpty(FlashMessage::show());
	}

}