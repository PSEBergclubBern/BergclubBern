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
                if($this->data['tab'] == 'data' && $this->data['edit']){
                    $this->data['address_roles'] = Role::findByType(Role::TYPE_ADDRESS);

                    $this->data['required'] = [
                        'bcb_all' => [
                            'address_type' => 'Adresstyp',
                            'leaving_reason' => 'Austrittsgrund',
                            'program_shipment' => 'Versand Programm',
                            'company' => 'Firma',
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'address_addition' => 'Zusatz',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                            'phone_private' => 'Telefon P',
                            'phone_work' => 'Telefon G',
                            'phone_mobile' => 'Telefon M',
                            'email' => 'Email',
                            'birthdate' => 'Geburtstag',
                            'comments' => 'Bemerkungen',
                        ],
                        'bcb_unset' => [
                            'leaving_reason' => 'Austrittsgrund',
                            'company' => 'Firma',
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                            'birthdate' => 'Geburtstag',
                        ],
                        'bcb_institution' => [
                            'company' => 'Firma',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                        ],
                        'bcb_inserent' => [
                            'company' => 'Firma',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                        ],
                        'bcb_interessent' => [
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                        ],
                        'bcb_interessent_jugend' => [
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                        ],
                        'bcb_aktivmitglied' => [
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                            'birthdate' => 'Geburtstag',
                        ],
                        'bcb_aktivmitglied_jugend' => [
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                            'birthdate' => 'Geburtstag',
                        ],
                        'bcb_ehrenmitglied' => [
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                            'street' => 'Strasse',
                            'zip' => 'PLZ',
                            'location' => 'Ort',
                            'birthdate' => 'Geburtstag',
                        ],
                        'bcb_ehemalig' => [
                            'leaving_reason' => 'Austrittsgrund',
                            'gender' => 'Anrede',
                            'first_name' => 'Vorname',
                            'last_name' => 'Nachname',
                        ],
                    ];

                }elseif($this->data['tab'] == 'functions' && $this->data['edit']) {
                    $this->data['user_functionary_roles'] = $this->data['user']->functionary_roles;
                    $this->data['functionary_roles'] = Role::findByType(Role::TYPE_FUNCTIONARY);
                }



            }else{
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Der gewünschte Datensatz existiert nicht.');
                $this->view = 'pages.empty';
            }
        }

        $this->checkRights();
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
        /* @var User $user */
        $user = $this->data['user'];

        /* validate form data */
        $role = Role::find($_POST['address_type']);

        /* get form data */
        foreach($_POST as $key => $value){
            $user->$key = $value;
        }

        if ( !$role ){

            /* if no role is selected, an error message is displayed */

            $user->removeRole( Role::find( $user->address_role_key ) , false );
            $this->data['user'] = $user;
            FlashMessage::add(FlashMessage::TYPE_ERROR, "Bitte einen Adresstypen auswählen!");

        } else {

            /* validate required fields */
            $fieldsToValidate = $this->data['required'][$role->getKey()];
            $errorMessage = "";

            foreach( $fieldsToValidate as $key => $label){
                if ( $user->$key == false ){
                    if( !$errorMessage ){
                        $errorMessage = $label;
                    } else {
                        $errorMessage = $errorMessage + ", " + $label;
                    }
                }
            }


            /* check if validation was successful */
            if ( $errorMessage ){
                $errorMessage = "Folgende Pflichtfelder müssen noch ausgefüllt werden: " . $errorMessage;
                $this->data['user'] = $user->addRole( $role, false );
                $this->data['user'] = $user;
                FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMessage);
            } else{

                $this->data['user'] = $user->addRole( $role, true );

                /* save user */
                $user->save();
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Benutzerdaten wurden erfolgreich gespeichert.');
                Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));

            }

        }

    }

    private function postFunctions(){
        /* @var User $user */
        $user = $this->data['user'];

        foreach($user->functionary_roles as $role){
            /* @var Role $role */
            if(!isset($_POST['functionary_roles']) || !in_array($role->getKey(), $_POST['functionary_roles'])){
                $user->removeRole($role);
            }
        }

        if(isset($_POST['functionary_roles'])) {
            foreach ($_POST['functionary_roles'] as $slug) {
                $role = Role::find($slug);
                if ($role) {
                    $user->addRole($role);
                }
            }
        }

        $user->save();

        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Funktionsrollen wurden erfolgreich gespeichert.');
        Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));
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

    private function checkRights(){
        $currentUser = User::findCurrent();
        if(!$currentUser->hasCapability('adressen_read')){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte um diese Seite anzuzeigen.');
            $this->abort();
        }

        $this->data['showEdit'] = false;
        if($currentUser->hasCapability('adressen_edit')){
            $this->data['showEdit'] = true;
        }elseif(isset($_GET['action']) || isset($this->data['edit']) && $this->data['edit']){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte um diese Seite anzuzeigen.');
            $this->abort();
        }
    }

    private function abort(){
        $this->data['title'] = "Ungenügende Rechte";
        $this->view = "pages.empty";
        $this->render();
    }
}