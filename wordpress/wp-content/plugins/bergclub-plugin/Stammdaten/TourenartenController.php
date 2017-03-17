<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 17.03.2017
 * Time: 12:31
 */

namespace BergclubPlugin\Stammdaten;


use BergclubPlugin\MVC\AbstractController;

class TourenartenController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.tourenarten';

    protected function first()
    {
        $this->data['title'] = "Tourenarten";
    }

    protected function get()
    {

    }

    protected function post()
    {

    }

    protected function last()
    {

    }

}