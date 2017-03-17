<?php

namespace BergclubPlugin\DemoMVC\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\WPModels\Option;

class FormController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.form';

    protected function first(){
        $this->data['title'] = "MVC Form";
        $this->data['key'] = Option::get('mvcdemo_key', "Ursprünglicher Wert");
    }

    protected function get(){}

    protected function post(){
        $this->data['key'] = trim($_POST['key']);
        //Verarbeiten der Daten
        if(empty($this->data['key'])){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Kein Wert definiert.');
        }else{
            Option::set('mvcdemo_key', $this->data['key']);
            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Wert gespeichert.');
        }
    }

    protected function last(){}
}