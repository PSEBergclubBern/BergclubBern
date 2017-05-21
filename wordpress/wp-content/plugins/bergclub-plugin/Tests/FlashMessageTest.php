<?php

namespace BergclubPlugin\Tests;

use BergclubPlugin\FlashMessage;
use PHPUnit\Framework\TestCase;

class FlashMessageTest extends TestCase
{

    /**
     * @test
     */
    public function warning()
    {
        FlashMessage::add(FlashMessage::TYPE_WARNING, 'Warning Message');
        $this->assertEquals('<div class="notice notice-warning"><p>Warning Message</p></div><p></p>', FlashMessage::show());
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function error()
    {
        FlashMessage::add(FlashMessage::TYPE_ERROR, 'Error Message');
        $this->assertEquals('<div class="notice notice-error"><p>Error Message</p></div><p></p>', FlashMessage::show());
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function info()
    {
        FlashMessage::add(FlashMessage::TYPE_INFO, 'Info Message');
        $this->assertEquals('<div class="notice notice-info"><p>Info Message</p></div><p></p>', FlashMessage::show());
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function success()
    {
        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Success Message');
        $this->assertEquals('<div class="notice notice-success"><p>Success Message</p></div><p></p>', FlashMessage::show());
        $this->assertEmpty(FlashMessage::show());
    }

    /**
     * @test
     */
    public function twoMessages()
    {
        FlashMessage::add(FlashMessage::TYPE_INFO, 'Info Message');
        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Success Message');
        $this->assertEquals('<div class="notice notice-info"><p>Info Message</p></div><div class="notice notice-success"><p>Success Message</p></div><p></p>', FlashMessage::show());
        $this->assertEmpty(FlashMessage::show());
    }
}
