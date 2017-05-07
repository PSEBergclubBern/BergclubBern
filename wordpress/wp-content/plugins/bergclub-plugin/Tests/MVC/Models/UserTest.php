<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 07.05.17
 * Time: 11:30
 */

namespace BergclubPlugin\Tests\MVC\Models;

use PHPUnit\Framework\TestCase;
use BergclubPlugin\MVC\Models\User;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    /**
     * @test
     */
    public function programShipmentOneEqualsToYes()
    {
        $this->user->program_shipment = '1';
        $this->assertEquals('Ja', $this->user->program_shipment);
    }

    /**
     * @test
     */
    public function programShipmentZeroEqualsToNo()
    {
        $this->user->program_shipment = '0';
        $this->assertEquals('Nein', $this->user->program_shipment);
    }
}