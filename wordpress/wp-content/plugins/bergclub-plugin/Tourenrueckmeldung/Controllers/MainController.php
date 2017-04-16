<?php

namespace BergclubPlugin\Tourenrueckmeldung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User;

class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

    protected function first(){
        $this->data['title'] = "Tourenrückmeldungen";

        if(empty($_GET['tab'])){
            $_GET['tab'] = 'tab1';
        }
        $this->data['tab'] = $_GET['tab'];
        $this->data['tab_file'] = 'includes.detail-main-' . $this->data['tab'];
    }

    protected function get(){

    }

    protected function post(){

    }

    protected function last(){

    }
}