<?php

namespace BergclubPlugin\Adressverwaltung\Controllers;

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
        $view = $this->getGET('view', 'main');

        $this->view = 'pages.' . $view;

        if($this->view == 'pages.main') {
            $this->data['title'] = "Adressverwaltung";
            $this->data['users'] = User::findAll();

        }elseif($this->view == 'pages.detail'){
            $this->prepareInclude();

            $user = $this->data['user'] = User::find($_GET['id']);

            if($user) {
                $this->data['title'] = $user->last_name . ' ' . $user->first_name;
                //Domi
                $this->data['functions'] = $user->functionary_roles;
                $this->data['address_roles'] = Role::findByType('address');

            }else{
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Der gewünschte Datensatz existiert nicht.');
                $this->view = 'pages.empty';
            }
        }
    }

    protected function get(){
        $action = $this->getGET('action', null);
        if($action == 'delete'){
            User::remove($_GET['id']);
            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Datensatz erfolgreich gelöscht.');
            Helpers::redirect('?page=' . $_GET['page']);
        }
    }

    protected function post(){
        $method = "post" . strtoupper(substr($this->data['tab'], 0, 1)) . substr($this->data['tab'], 1);
        if(method_exists($this, $method)){
            $this->$method();
        }
    }

    protected function last(){

    }


    private function postData(){



        if ( true ){



        }

        //mache das hier, damit IDE erkennt dass es ein User objekt ist.
        /* @var User $user */
        $user = $this->data['user'];

        foreach($_POST as $key => $value){
            $user->$key = $value;
        }

        $role = Role::find($_POST['address_type']);
        if($role) {
            $user->addRole($role);
        }else{
            print $_POST['address_type'];
            exit;
        }

        $user->save();
        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Benutzerdaten wurden erfolgreich gespeichert.');

        //weiterleitung auf show modus
        Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));
    }

    private function postFunctions(){
        FlashMessage::add(FlashMessage::TYPE_INFO, 'Funktionsrollen wurden geposted.');
        //HIER DEN FORMULARPOST DER FUNKTIONSROLLEN VERARBEITEN
    }

    private function postHistory(){
        FlashMessage::add(FlashMessage::TYPE_INFO, 'Historie wurde geposted.');
        //HIER DEN FORMULARPOST DER HISTORIE VERWARBEITEN
    }



    private function prepareInclude(){
        $this->data['tab'] = $this->getGET('tab', 'data');
        $this->data['edit'] = $this->getGET('edit', 0);
        $viewType = "show";
        if($this->data['edit']){
            $viewType = "edit";
        }

        $this->data['tab_file'] = str_replace('pages.', 'includes.', $this->view) . '-' . $this->data['tab'] . '-' . $viewType;
    }

    private function getGET($key, $default){
        if(isset($_GET[$key])){
            return $_GET[$key];
        }
        return $default;
    }
}