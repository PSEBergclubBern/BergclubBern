<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 16.03.17
 * Time: 08:17
 */

namespace BergclubPlugin\Tests\Commands;


use PHPUnit\Framework\TestCase;
use BergclubPlugin\Commands\Import;

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
    public function isInvokable()
    {
        $this->assertTrue(is_callable($this->import));
    }
}