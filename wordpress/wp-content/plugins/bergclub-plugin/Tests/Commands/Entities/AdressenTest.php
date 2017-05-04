<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use BergclubPlugin\Commands\Entities\Adressen;
use PHPUnit\Framework\TestCase;

class AdressenTest extends TestCase
{
    /**
     * @var Adressen
     */
    private $entity;

    public function setUp()
    {
        $this->entity = new Adressen();
    }

    /**
     * @test
     */
    public function generateHistoryWithNoDatesWillReturnEmptyArray()
    {
        $this->assertTrue(is_array($this->entity->getUserHistory()));
        $this->assertEquals(0, count($this->entity->getUserHistory()));
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromVorstandWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->vorstandFrom = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromSupportWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->supportFrom = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }


    /**
     * @test
     */
    public function generateHistoryWithDateFromFreeMemberWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->freeMemberDate = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromHonorMemberWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->honorMemberDate = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromLeaderWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->leaderFrom = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromLeaderYouthWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->leaderYouthFrom = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromActiveMemberYouthWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->activeMemberDate = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromActiveYouthMemberYouthWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->activeYouthMemberDate = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }

    /**
     * @test
     */
    public function generateHistoryWithDateFromInteressentMemberYouthWillReturnHistory()
    {
        $expectedArray = array(
            'bcb_aktivmitglied' => array(
                'date_from' => '2016-11-13',
                'date_to' => null,
            )
        );
        $this->entity->interessentDate = '2016-11-13';
        $this->assertEquals(1, count($this->entity->getUserHistory()));
        $this->assertEquals($expectedArray, $this->entity->getUserHistory());
    }
}