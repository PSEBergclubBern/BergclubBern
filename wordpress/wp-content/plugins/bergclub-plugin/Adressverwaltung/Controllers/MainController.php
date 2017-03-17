<?php

namespace BergclubPlugin\Adressverwaltung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Models\User;

class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

    protected function first(){
        if(isset($_GET['view'])){
            $this->view = 'pages.' . $_GET['view'];
        }

        if($this->view == 'pages.main') {
            $this->data['title'] = "Adressverwalung";
            $this->data['users'] = User::findAll();
        }elseif($this->view == 'pages.detail'){
            $user = $this->data['user'] = User::find($_GET['id']);
            if($user) {
                $this->data['title'] = $user->last_name . ' ' . $user->first_name;
            }else{
                $this->data['title'] = "Datensatz nicht gefunden";
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Der gewünschte Datensatz existiert nicht.');
            }
        }
    }

    protected function get(){}

    protected function post(){}

    protected function last(){}

}