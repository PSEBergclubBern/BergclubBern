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
        if($method = $this->getTabMethod('get')){
            $this->$method();
        }else {
            $action = $this->getGET('action', null);
            if ($action == 'delete') {
                User::remove($_GET['id']);
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Datensatz erfolgreich gelöscht.');
                Helpers::redirect('?page=' . $_GET['page']);
            }
        }
    }

    protected function post(){
        if($method = $this->getTabMethod('post')){
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

    private function getHistory(){
        $action = $this->getGET('action', null);
        if($action == "delete"){
            /* @var User $user */
            $user = $this->data['user'];
            $history = $user->history;
            if(key_exists($_GET['key'], $history)){
                unset($history[$_GET['key']]);
                $user->history = $history;
                $user->save();
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Datensatz erfolgreich gelöscht.');
                Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));
            }
        }
    }

    private function postHistory(){
        /* @var User $user */
        $user = $this->data['user'];

        foreach($_POST['history'] as &$item){
            $item = array_map('sanitize_text_field', $item);
        }

        $user->history = $_POST['history'];
        $user->save();

        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Historie wurde erfolgreich gespeichert.');
        Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));
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

    private function getTabMethod($requestType){
        if(isset($this->data['tab']) && !empty($this->data['tab'])) {
            $method = strtolower($requestType) . strtoupper(substr($this->data['tab'], 0, 1)) . substr($this->data['tab'], 1);
            if (method_exists($this, $method)) {
                return $method;
            }
        }

        return false;
    }
}