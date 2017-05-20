<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use BergclubPlugin\Commands\Logger;
use BergclubPlugin\Commands\Processor\AddressProcessor;
use PHPUnit\Framework\TestCase;

class AddressProcessorTest extends TestCase
{
    /**
     * @var AddressProcessor
     */
    private $processor;

    public function setUp()
    {
        $logger = $this->createMock(Logger::class);
        $this->processor = new AddressProcessor($logger);
    }

    /**
     * @test
     */
    public function standardAddressHasRightEntity()
    {
        $values = array(array($this->getStandardAddress()));

        $entities = $this->processor->process($values);
        $this->assertEquals(1, count($entities));

        $entity = current($entities);

        $this->assertEquals('Mustermann', $entity->lastName);
        $this->assertEquals('Max', $entity->firstName);
    }

    private function getStandardAddress()
    {
        return array(
            'id' => '1',
            'versenden' => '1',
            'beitrag' => '0',
            'name' => 'Mustermann',
            'vorname' => 'Max',
            'anrede' => 'Herr',
            'nr' => '',
            'kategorie' => '1',
            'strasse' => 'Musterweg',
            'plz' => '3012',
            'ort' => 'Bern',
            'tel_p' => '031 123 45 67',
            'tel_g' => '',
            'natel' => '',
            'email' => '',
            'geburtsdatum' => '1980-06-12',
            'ahv' => '',
            'leiter' => '',
            'beschreibung' => '',
            'js_leiter' => '',
            'js_beschreibung' => '',
            'js_jahr' => '0',
            'pers_nr' => '',
            'vorstand' => '',
            'funktion' => '',
            'support' => '',
            'aufgabe' => '',
            'bemerkungen' => '    ',
            'idat' => '0000-00-00',
            'bdat' => '0000-00-00',
            'jdat' => '0000-00-00',
            'edat' => '0000-00-00',
            'egrund' => '',
            'fdat' => '0000-00-00',
            'fgrund' => '',
            'adat' => '0000-00-00',
            'agrund' => '',
            'ddat' => '0000-00-00',
            'dgrund' => '',
            'ldat' => '0000-00-00',
            'lbis' => '0000-00-00',
            'jsdat' => '0000-00-00',
            'jsbis' => '0000-00-00',
            'vdat' => '0000-00-00',
            'vbis' => '0000-00-00',
            'sdat' => '0000-00-00',
            'sbis' => '0000-00-00',
            'vpos' => '0',
            'spos' => '0',
            'id2' => '1',
            'zusatz' => ''
        );
    }

    /**
     * @test
     */
    public function standardAddressWillResultInRightGender()
    {
        $values = array(array($this->getStandardAddress()));

        $entities = $this->processor->process($values);
        $entity = current($entities);

        $this->assertEquals('M', $entity->toArray()['gender']);
    }

    /**
     * @test
     */
    public function sendProgramIsFalseWhen0()
    {
        $address = $this->getStandardAddress();
        $address['versenden'] = 0;
        $values = array(array($address));

        $entities = $this->processor->process($values);
        $entity = current($entities);

        $this->assertEquals('0', $entity->toArray()['program_shipment']);
    }

    /**
     * @test
     */
    public function sendProgramIsTrueWhen1()
    {
        $values = array(array($this->getStandardAddress()));

        $entities = $this->processor->process($values);
        $entity = current($entities);

        $this->assertEquals('1', $entity->toArray()['program_shipment']);
    }
}