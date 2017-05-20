<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 22.03.17
 * Time: 08:29
 */

namespace BergclubPlugin\Tests\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\Touren\MetaBoxes\Common;
use BergclubPlugin\Touren\MetaBoxes\MeetingPoint;
use PHPUnit\Framework\TestCase;

class MeetingPointTest extends TestCase
{
    /**
     * @var Common
     */
    private $meeting;

    /**
     * @Before
     */
    public function setUp()
    {
        FlashMessage::show();
        $this->meeting = new MeetingPoint();
    }

    /**
     * @test
     */
    public function hasStringAsIdentifier()
    {
        $this->assertNotEmpty($this->meeting->getUniqueMetaBoxName());
        $this->assertTrue(is_string($this->meeting->getUniqueMetaBoxName()));
    }

    /**
     * @test
     */
    public function hasStringAsTitle()
    {
        $this->assertNotEmpty($this->meeting->getUniqueMetaBoxTitle());
        $this->assertTrue(is_string($this->meeting->getUniqueMetaBoxTitle()));
    }

    /**
     * @test
     */
    public function fieldsReturnFieldAsArray()
    {
        $this->assertNotEmpty($this->meeting->getUniqueFieldNames());
        $this->assertTrue(is_array($this->meeting->getUniqueFieldNames()));
    }

    /**
     * @test
     */
    public function treffpunktZeitIsNoValidTime()
    {
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::TIME => 'test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktZeitIsValidTime()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::TIME => '15:32'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function rückkehrIsNoValidTime()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::RETURNBACK => 'test'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function rückkehrIsValidTime()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::RETURNBACK => '15:32'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktAnderesNoAlternativTreffpunkt()
    {
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 99), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktAnderesWithAlternativTreffpunkt()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 99, MeetingPoint::MEETPOINT_DIFFERENT => 'Welle'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function treffpunktSet()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::MEETPOINT => 'Test'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function alternativTreffpunktSetButNoTreffpunk()
    {
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::MEETPOINT_DIFFERENT => 'Test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function meetingPointIsOptionalForDraftButNotForPublish()
    {
        $this->assertTrue($this->meeting->isValid(array(MeetingPoint::MEETPOINT => ''), "draft"));
        $this->assertFalse($this->meeting->isValid(array(MeetingPoint::MEETPOINT => ''), "publish"));
    }

    /**
     * @test
     */
    public function meetingPointOtherIsOptionalForDraftButNotForPublish()
    {
        $values = array(MeetingPoint::MEETPOINT => 99, MeetingPoint::MEETPOINT_DIFFERENT => '');
        $this->assertTrue($this->meeting->isValid($values, "draft"));
        $this->assertFalse($this->meeting->isValid($values, "publish"));
    }

    /**
     * @test
     */
    public function returnBackIsOptionalForDraftButNotForPublish()
    {
        $values = array(MeetingPoint::RETURNBACK => '');
        $this->assertTrue($this->meeting->isValid($values, "draft"));
        $this->assertFalse($this->meeting->isValid($values, "publish"));
    }
}