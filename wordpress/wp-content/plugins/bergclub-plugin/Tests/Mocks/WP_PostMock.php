<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 18:26
 */

namespace BergclubPlugin\Tests\Mocks;


class WP_PostMock
{
    public $ID;
    public $post_status;
    public $post_date;
    public $post_modified;

    public function __construct($ID, $post_status, $post_date, $post_modified)
    {
        $this->ID = $ID;
        $this->post_status = $post_status;
        $this->post_date = $post_date;
        $this->post_modified = $post_modified;
    }
}