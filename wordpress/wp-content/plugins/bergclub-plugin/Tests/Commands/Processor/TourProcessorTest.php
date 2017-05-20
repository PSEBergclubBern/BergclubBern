<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use BergclubPlugin\Commands\Entities\Tour;
use BergclubPlugin\Commands\Logger;
use BergclubPlugin\Commands\Processor\TourProcessor;
use BergclubPlugin\Touren\MetaBoxes\MeetingPoint;
use PHPUnit\Framework\TestCase;

class TourProcessorTest extends TestCase
{
    /**
     * @var TourProcessor
     */
    private $processor;

    public function setUp()
    {
        $logger = $this->createMock(Logger::class);
        $this->processor = new TourProcessor($logger);
    }

    /**
     * @test
     */
    public function reportIsFoundWithSameDate()
    {
        $touren = array(
            $this->getStandardTour()
        );

        $bericht = array(
            array(
                'id' => '1',
                'datum' => '2006-04-21',
                'titel' => 'Skitour',
                'text' => urlencode('text für bericht')
            )
        );

        $entities = $this->processor->process(array($touren, $bericht, array(), array(), array()));
        $this->assertEquals(1, count($entities));

        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertNotNull($entity->tourBericht);
    }

    private function getStandardTour()
    {
        return array(
            'id' => '19',
            'user_id' => '46',
            'von' => '2006-04-21',
            'bis' => '2006-04-22',
            'titel' => 'Skitour',
            'leiter_a' => '188',
            'leiter_b' => '0',
            'bcb' => '1',
            'bcbj' => '1',
            'art_id' => '3',
            'anf_t' => '2',
            'bes' => '',
            'anf_k' => '3',
            'auf' => '1436 m / 5 1/2 Std.',
            'ab' => '1436 m / 2 Std.',
            'karte' => 'LK-Blatt 1',
            'js' => '',
            'treff_o' => 'Freitag, 19:15 h Ostermundigen, Banhof SBB',
            'prog' => 'Programm',
            'rueck_o' => 'Bern an ca. 18:00 h, Samstag',
            'andere' => '',
            'ausr' => 'Skitour',
            'verpf' => 'Rucksack',
            'ueb' => 'Restaurant',
            'kosten' => '110',
            'fuer' => 'Fahrt, Übernachtung, Morgenessen',
            'besonderes' => 'Etwas zum Bräteln mitnehmen',
            'adat' => '2006-04-18',
            'an' => 'Vorname Nachname',
            'tel' => '031 123 45 67',
            'email' => '',
            'rueck' => '1',
            'anz' => '7',
            'tl' => '',
            'tn' => '',
            'abw' => '',
            'ang' => 'Tagwacht um 4.00 Kosten Teilnehmer 40+25=65.-',
            'ber' => 'Tourberichterfasser',
            'ver' => 'Tourberichterfasser',
            'vs' => '',
            'vb' => '0.00',
            'rs' => 'Auto',
            'rb' => '40.00',
            'us' => '',
            'ub' => '25.00',
            'ds' => '',
            'db' => '7.00',
            'hf' => '12.00',
            'empf' => 'Max Mustermann / Strasse / 3072 Ostermundigen',
            'zh' => '',
            'pc' => '11-11111-1',
            'cl' => '',
            'examine' => '28.04.2006 A Name',
            'archive' => '13.05.2006 P Name',
            'a_date' => '2001-01-02',
            'a_name' => 'A Name',
            'p_date' => '2001-01-01',
            'p_name' => 'P Name',
            'ausb' => NULL,
        );
    }

    /**
     * @test
     */
    public function noReportIsFoundWhenNotSameDate()
    {
        $touren = array(
            $this->getStandardTour()
        );

        $bericht = array(
            array(
                'id' => '1',
                'datum' => '2006-03-21',
                'titel' => 'Supertour',
                'text' => urlencode('text für bericht')
            )
        );

        $entities = $this->processor->process(array($touren, $bericht, array(), array(), array()));
        $this->assertEquals(1, count($entities));

        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertNull($entity->tourBericht);
    }

    /**
     * @test
     */
    public function ifMoreThanOneReportIsFoundTakeWithMostMatchingTitleAndNotFirstOccur()
    {
        $touren = array(
            $this->getStandardTour()
        );

        $bericht = array(
            array(
                'id' => '1',
                'datum' => '2006-04-21',
                'titel' => 'Superwanderung',
                'text' => urlencode('text für bericht')
            ),
            array(
                'id' => '2',
                'datum' => '2006-04-21',
                'titel' => 'Skitour Appenzell',
                'text' => urlencode('text für bericht')
            )
        );

        $entities = $this->processor->process(array($touren, $bericht, array(), array(), array()));
        $this->assertEquals(1, count($entities));

        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertNotNull($entity->tourBericht);
        $this->assertEquals(2, $entity->tourBericht->id);
    }

    /**
     * @test
     */
    public function ifMoreThanOneReportIsFoundTakeWithMostMatchingTitleAndNotLastOccur()
    {
        $touren = array(
            $this->getStandardTour()
        );

        $bericht = array(
            array(
                'id' => '1',
                'datum' => '2006-04-21',
                'titel' => 'Skitour Appenzell',
                'text' => urlencode('text für bericht')
            ),
            array(
                'id' => '2',
                'datum' => '2006-04-21',
                'titel' => 'Superwanderung',
                'text' => urlencode('text für bericht')
            )
        );

        $entities = $this->processor->process(array($touren, $bericht, array(), array(), array()));
        $this->assertEquals(1, count($entities));

        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertNotNull($entity->tourBericht);
        $this->assertEquals(1, $entity->tourBericht->id);
    }

    /**
     * @test
     */
    public function setMeetpointToDifferentIfThereIsText()
    {
        $touren = array(
            $this->getStandardTour()
        );

        $entities = $this->processor->process(array($touren, array(), array(), array(), array()));
        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertEquals(MeetingPoint::MEETPOINT_DIFFERENT_KEY, $entity->meetingPointKey);
        $this->assertEquals('Freitag, 19:15 h Ostermundigen, Banhof SBB', $entity->meetingPoint);
    }

    /**
     * @test
     */
    public function setMeetpointToBernIfNoMeetpointIsDefined()
    {
        $tour = $this->getStandardTour();
        $tour['treff_o'] = '';
        $touren = array(
            $tour
        );

        $entities = $this->processor->process(array($touren, array(), array(), array(), array()));
        $entity = current($entities);
        /** @var Tour $entity */
        $this->assertEquals(1, $entity->meetingPointKey);
        $this->assertTrue(empty($entity->meetingPoint));
    }
}