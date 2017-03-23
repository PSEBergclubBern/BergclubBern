<?php

namespace BergclubPlugin\Adressverwaltung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\User;

class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

    protected function first(){
        if(isset($_GET['action']) && $_GET['action'] == 'delete'){
            User::remove($_GET['id']);
            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Datensatz erfolgreich gelöscht.');
            Helpers::redirect('?page=' . $_GET['page']);
        }

        if(isset($_GET['view'])){
            $view = $_GET['view'];
            if($view == "detail"){
                $this->data['edit'] = false;
                $tab = "data";
                if(isset($_GET['tab'])){
                    $tab = $_GET['tab'];
                }
                $viewtype = "show";
                //TODO: check rights
                if(isset($_GET["edit"])){
                    $this->data['edit'] = true;
                    $viewtype = "edit";
                }

                $this->data['tab'] = $tab;
                $this->data['tab_file'] = 'includes.' . $view . '-' . $tab . '-' . $viewtype;
            }
            $this->view = 'pages.' . $view;
        }

        if($this->view == 'pages.main') {
            $this->data['title'] = "Adressverwaltung";
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