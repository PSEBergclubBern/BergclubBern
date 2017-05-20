<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use BergclubPlugin\Commands\Logger;
use BergclubPlugin\Commands\Processor\MitteilungProcessor;
use PHPUnit\Framework\TestCase;

class MitteilungProcessorTest extends TestCase
{
    /**
     * @var MitteilungProcessor
     */
    private $processor;

    public function setUp()
    {
        $logger = $this->createMock(Logger::class);
        $this->processor = new MitteilungProcessor($logger);
    }

    /**
     * @test
     */
    public function standardMessageHasOneEntity()
    {
        $mitteilung = array(
            'id' => '1',
            'datum' => '01-01-2017',
            'titel' => 'Testtitel',
            'text' => 'judihui',
        );

        $entities = $this->processor->process(array(array($mitteilung)));

        $this->assertEquals(1, count($entities));
    }

    /**
     * @test
     */
    public function messageHasRightId()
    {
        $mitteilung = array(
            'id' => '1',
            'datum' => '01-01-2017',
            'titel' => 'Testtitel',
            'text' => 'judihui',
        );

        $entities = $this->processor->process(array(array($mitteilung)));

        $this->assertEquals(1, current($entities)->id);
    }

    /**
     * @test
     */
    public function messageHasRightTitle()
    {
        $mitteilung = array(
            'id' => '1',
            'datum' => '01-01-2017',
            'titel' => 'Testtitel',
            'text' => 'judihui',
        );

        $entities = $this->processor->process(array(array($mitteilung)));

        $this->assertEquals('Testtitel', current($entities)->titel);
    }

    /**
     * @test
     */
    public function messageHasRightText()
    {
        $mitteilung = array(
            'id' => '1',
            'datum' => '01-01-2017',
            'titel' => 'Testtitel',
            'text' => 'judihui',
        );

        $entities = $this->processor->process(array(array($mitteilung)));

        $this->assertEquals('judihui', current($entities)->text);
    }

    /**
     * @test
     */
    public function messageHasRightDatum()
    {
        $mitteilung = array(
            'id' => '1',
            'datum' => '01-01-2017',
            'titel' => 'Testtitel',
            'text' => 'judihui',
        );

        $entities = $this->processor->process(array(array($mitteilung)));

        $this->assertEquals('01-01-2017', current($entities)->datum);
    }
}