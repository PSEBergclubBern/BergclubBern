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
use PHPUnit\Framework\TestCase;

class CommonTest extends TestCase
{
    /**
     * @var Common
     */
    private $common;

    /**
     * @Before
     */
    public function setUp()
    {
        $this->common = new Common();
    }

    /**
     * @test
     */
    public function hasStringAsIdentifier()
    {
        $this->assertNotEmpty($this->common->getUniqueMetaBoxName());
        $this->assertTrue(is_string($this->common->getUniqueMetaBoxName()));
    }

    /**
     * @test
     */
    public function hasStringAsTitle()
    {
        $this->assertNotEmpty($this->common->getUniqueMetaBoxTitle());
        $this->assertTrue(is_string($this->common->getUniqueMetaBoxTitle()));
    }

    /**
     * @test
     */
    public function fieldsReturnFieldAsArray()
    {
        $this->assertNotEmpty($this->common->getUniqueFieldNames());
        $this->assertTrue(is_array($this->common->getUniqueFieldNames()));
    }

    /**
     * @test
     */
    public function fieldDateIsNotAcceptingInvalidDate()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => 'test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function fieldDateIsNotAcceptingInvalidDateForDraft()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => 'test'), "draft"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function fieldDateIsAcceptingValidDates()
    {
        $this->assertTrue($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function fieldDateIsAcceptingValidDatesForDraft()
    {
        $this->assertTrue($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000'), "draft"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsAcceptingValidDates()
    {
        $this->assertTrue($this->common->isValid(array(Common::DATE_TO_IDENTIFIER => '01.01.2000'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsEqualDateFrom()
    {
        $this->assertTrue($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000',
            Common::DATE_TO_IDENTIFIER => '01.01.2000',
            Common::SLEEPOVER => ""), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsEqualDateFromButSleepoverJa()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000',
            Common::DATE_TO_IDENTIFIER => '01.01.2000',
            Common::SLEEPOVER => "Something"), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsAfterDateFromAndSleepoverIsSet()
    {
        $this->assertTrue($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000',
            Common::DATE_TO_IDENTIFIER => '01.02.2000',
            Common::SLEEPOVER => "Test"), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsAfterDateFromAndSleepoverIsEmpty()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000',
            Common::DATE_TO_IDENTIFIER => '01.02.2000',
            Common::SLEEPOVER => ""), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsAfterDateFromAndSleepoverIsNotSet()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.01.2000',
            Common::DATE_TO_IDENTIFIER => '01.02.2000'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateToIsBeforeDateFrom()
    {
        $this->assertFalse($this->common->isValid(array(Common::DATE_FROM_IDENTIFIER => '01.02.2000',
            Common::DATE_TO_IDENTIFIER => '01.01.2000'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function signUpUntilHasInValidDate()
    {
        $this->assertFalse($this->common->isValid(array(Common::SIGNUP_UNTIL => 'test'), "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function signUpUntilHasValidValue()
    {
        $this->assertTrue($this->common->isValid(array(Common::SIGNUP_UNTIL => '01.01.2017'), "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function signUpUntilIsAfterTourBeginn()
    {
        $values = array(Common::SIGNUP_UNTIL => '01.01.2017', Common::DATE_FROM_IDENTIFIER => '01.06.2017');
        $this->assertTrue($this->common->isValid($values, "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function signUpUntilIsEqualToTourBeginn()
    {
        $values = array(Common::SIGNUP_UNTIL => '01.01.2017', Common::DATE_FROM_IDENTIFIER => '01.01.2017');
        $this->assertTrue($this->common->isValid($values, "publish"));
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function signUpUntilIsBeforeTourBeginn()
    {
        $values = array(Common::SIGNUP_UNTIL => '01.01.2017', Common::DATE_FROM_IDENTIFIER => '01.01.2000');
        $this->assertFalse($this->common->isValid($values, "publish"));
        $this->assertNotEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function dateFromIsRequired()
    {
        $values = array(Common::DATE_FROM_IDENTIFIER => '');
        $this->assertFalse($this->common->isValid($values, "publish"));
        $this->assertFalse($this->common->isValid($values, "draft"));
    }

    /**
     * @test
     */
    public function leaderIsRequired()
    {
        $values = array(Common::LEADER => '');
        $this->assertFalse($this->common->isValid($values, "publish"));
        $this->assertFalse($this->common->isValid($values, "draft"));
    }

    /**
     * @test
     */
    public function signUpToIsRequiredForPublish()
    {
        $values = array(Common::SIGNUP_TO => '');
        $this->assertFalse($this->common->isValid($values, "publish"));
    }

    /**
     * @test
     */
    public function signUpToIsOptionalForDraft()
    {
        $values = array(Common::SIGNUP_TO => '');
        $this->assertTrue($this->common->isValid($values, "draft"));
    }

    /**
     * @test
     */
    public function signUpUntilIsRequiredForPublish()
    {
        $values = array(Common::SIGNUP_UNTIL => '');
        $this->assertFalse($this->common->isValid($values, "publish"));
    }

    /**
     * @test
     */
    public function signUpUntilIsOptionalForDraft()
    {
        $values = array(Common::SIGNUP_UNTIL => '');
        $this->assertTrue($this->common->isValid($values, "draft"));
    }
}