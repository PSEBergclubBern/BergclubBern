<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 22.03.17
 * Time: 08:29
 */

namespace BergclubPlugin\Tests\Touren\MetaBoxes;

use PHPUnit\Framework\TestCase;

class TimestampsTest extends TestCase
{

    /**
     * @test
     */
    public function testValidDuration1()
    {
        $duration = "15:34";
        $a = preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", $duration) === 1;
        $this->assertTrue($a);
    }

    /**
     * @test
     */
    public function testValidDuration2()
    {
        $duration = "5:34";
        $a = preg_match("/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/", $duration) === 1;
        $this->assertTrue($a);
    }

    /**
     * @test
     */
    public function testValidDuration3()
    {
        $duration = "0:30";
        $a = preg_match("/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/", $duration) === 1;
        $this->assertTrue($a);
    }

    /**
     * @test
     */
    public function testValidDuration4()
    {
        $duration = "0:0";
        $a = preg_match("/^([01]?[0-9]|2[0-3])\:+[0-5][0-9]$/", $duration) === 1;
        $this->assertFalse($a);
    }


}