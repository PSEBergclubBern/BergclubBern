<?php

namespace BergclubPlugin\DemoMVC\Controllers;

use BergclubPlugin\MVC\AbstractController;

class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

    protected function first(){
        $this->data['title'] = "Hauptseite";
    }

    protected function get(){}

    protected function post(){}

    protected function last(){}

}