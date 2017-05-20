<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use BergclubPlugin\Commands\Import;
use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase
{
    private $import;

    public function setUp()
    {
        $this->import = new Import();
    }

    /**
     * @test
     */
    public function isCallable()
    {
        $this->assertTrue(is_callable($this->import));
    }
}