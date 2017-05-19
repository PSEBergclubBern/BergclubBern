<?php

namespace BergclubPlugin\Adressverwaltung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User;

/**
 * Controls get and post actions and needed objects for the different views for the admin page "Adressverwaltung"
 *
 * @package BergclubPlugin\Adressverwaltung\Controllers
 */
class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';

    /**
     * Generates the needed data needed for all request types depending on the current view
     * @see AbstractController::first()
     */
    protected function first(){
        $view = $this->getGET('view', 'main');

        $this->view = 'pages.' . $view;

        if($this->view == 'pages.main') {
            //setup title and users for the main page
            $this->data['title'] = "Adressverwaltung";
            $this->data['users'] = User::findAll();

        }elseif($this->view == 'pages.detail' || $this->view == 'pages.new'){
            //setup common data for the detail and new user page.
            $this->prepareInclude();

            if($this->view == 'pages.detail') {
                //on the detail page we want the details of a specific user
                $user = $this->data['user'] = User::find($_GET['id']);
            }else{
                //on the new user page we want new (empty) user object
                $user = $this->data['user'] = new User();
            }


            if($user) {
                if($this->view == 'pages.detail') {
                    //we want the user last name and first name as title for the detail page
                    $this->data['title'] = $user->last_name . ' ' . $user->first_name;
                    //we also need the spouse information
                    $this->data['spouse'] = $user->spouse;
                }else{
                    $this->data['title'] = "Neuer Eintrag";
                }



                if(($this->data['tab'] == 'data' && $this->data['edit']) || $this->view == 'pages.new'){
                    //the tab is "Daten" and we are in edit mode or the new user view is set.

                    $action = $this->getGET('action', null);
                    if ( $action == 'deleteSpouse' ){
                        //no woman no cry
                        $spouse = $user->spouse;

                        if ( $spouse != null ){

                            $user->unsetSpouse();

                            $spouse->unsetSpouse();

                            FlashMessage::add(Flashmessage::TYPE_SUCCESS, 'Ehepartner/in erfolgreich gelöscht.');
                            Helpers::redirect( '?page=' . $_GET['page'] . '&view=detail&tab=data&id=' . $_GET['id'] . '&edit=1' );

                        } else {

                            FlashMessage::add(Flashmessage::TYPE_ERROR, 'Für diesen Benutzer ist kein/e Ehepartner/in erfasst.');
                            Helpers::redirect( '?page=' . $_GET['page'] . '&view=detail&tab=data&id=' . $_GET['id'] . '&edit=1' );

                        }

                    }

                    $this->data['address_roles'] = Role::findByType(Role::TYPE_ADDRESS);
                    $this->data['spouse'] = $user->spouse;

                    $this->data['required'] = $this->requiredFormFields();

                }elseif($this->data['tab'] == 'functions' && $this->data['edit']) {
                    //the tab is "Funktionen" and we are in edit mode.
                    $this->data['user_functionary_roles'] = $this->data['user']->functionary_roles;
                    $this->data['functionary_roles'] = Role::findByType(Role::TYPE_FUNCTIONARY);
                } elseif($this->data['tab'] == 'spouse' && $this->data['edit']){
                    //the tab is "Ehepartner" and we are in edit mode.
                    $users = User::findMitglieder();

                    foreach( $users as $key => $eventualSpouse ){
                        if ( $user == $eventualSpouse ) {
                            unset($users[$key]);
                        }
                    }
                    $this->data['users'] = array_values($users);
                }

            }else{
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Der gewünschte Datensatz existiert nicht.');
                $this->view = 'pages.empty';
            }
        }

        $this->checkRights();
    }

    /**
     * Handles GET request type.
     * <p>
     * Checks if a tab is active and if a specific method for this tab exists, if yes the specific method is called.
     * Otherwise get request actions are handled.
     *
     * @see AbstractController::get()
     */
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

    /**
     * Handles POST request type.
     * <p>
     * Checks if a tab is active and if a specific method for this tab exists, if yes the specific method is called.
     */
    protected function post(){
        if($method = $this->getTabMethod('post')){
            $this->$method();
        }
    }


    /**
     * @see AbstractController::last()
     */
    protected function last(){

    }


    /**
     * Handles post request for "Daten" tab.
     */
    private function postData(){
        /* @var User $user */
        $user = $this->data['user'];


        $role = Role::find($_POST['address_type']);

        //add post data to our user object
        foreach($_POST as $key => $value){
            if($key=='birthdate'){
                if(Helpers::isValidDate($value)){
                    $user->$key = trim($value);
                }elseif(!empty($value)){
                    FlashMessage::add(FlashMessage::TYPE_ERROR, "Das Geburtsdatum muss im Format tt.mm.jjjj und ein gültiges Datum sein.<br>Falls nur der Jahrgang bekannt ist, notieren Sie dies unter Bemerkungen und verwenden Sie 01.01.jjjj als Geburtsdatum.");
                    $user->$key = null;
                }
            }else{
                $user->$key = trim($value);
            }
        }

        if ( !$role ){

            // if no address role was selected, an error message is displayed

            $user->removeRole( Role::find( $user->address_role_key ) , false );
            $this->data['user'] = $user;
            FlashMessage::add(FlashMessage::TYPE_ERROR, "Bitte einen Adresstypen auswählen!");

        } else {

            //validate required fields
            $fieldsToValidate = $this->data['required'][$role->getKey()];
            $errorMessage = "";

            foreach( $fieldsToValidate as $key => $label){
                if (!$user->$key){
                    if( !$errorMessage ){
                        $errorMessage = $label;
                    } else {
                        $errorMessage .= ", " . $label;
                    }
                }
            }

            $foundError = false;

            // handle the case when validation was not successful
            if ( $errorMessage ){
                $errorMessage = "Folgende Pflichtfelder müssen noch ausgefüllt werden: " . $errorMessage;
                $this->data['user'] = $user->addRole( $role, false );
                $this->data['user'] = $user;
                FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMessage);
                $foundError = true;
            }

            // validate email separately
            if(!empty($user->email) && !filter_var($user->email, FILTER_VALIDATE_EMAIL)){
                FlashMessage::add(FlashMessage::TYPE_ERROR, "Die Emailadresse hat kein korrektes Format.");
                $foundError = true;
            }


            if(!$foundError){
                // everything is fine
                $this->data['user'] = $user->addRole( $role, true );

                $user->save();
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Benutzerdaten wurden erfolgreich gespeichert.');
                Helpers::redirect("?page=" . $_GET['page'] . "&view=detail&id=" . $user->ID);
            }

        }

    }

    /**
     * Handles post request for "Funktionen" tab.
     */
    private function postFunctions(){
        /* @var User $user */
        $user = $this->data['user'];

        // make sure that user doesn't add functionary roles on himself
        $currentUser = User::findCurrent();
        if ( $user == $currentUser ){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie können sich nicht selber Funktionsrollen zuweisen!');
            Helpers::redirect(str_replace('&edit=1', '', $_SERVER['REQUEST_URI']));
        }

        // remove existing roles from user which where not selected in the form
        foreach($user->functionary_roles as $role){
            /* @var Role $role */
            if(!isset($_POST['functionary_roles']) || !in_array($role->getKey(), $_POST['functionary_roles'])){
                $user->removeRole($role);
            }
        }

        // add selected roles to user
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

    /**
     * Handles GET request for "Historie" tab.
     */
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

    /**
     * Handles POST requestion for "Historie" tab.
     */
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

    /**
     * Handles POST request for "Ehepartner" tab.
     */
    private function postSpouse(){

        $spouseId = null;
        if ( isset($_POST['spouse']) ){
            $spouseId = $_POST['spouse'];
        }

        if ( !$spouseId ){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Bitte einen Ehepartner/in auswählen!');
        } else{
            $user = $this->data['user'];
            $spouse = User::find($spouseId);

            $user->spouse = $spouse;
            $user->main_address = true;

            $this->data['user'] = $user;
            $user->save();

            $spouse->spouse = $user;
            $user->main_address = false;
            $spouse->save();

            FlashMessage::add(Flashmessage::TYPE_SUCCESS, 'Ehepartner/in erfolgreich gespeichert.');
            Helpers::redirect( '?page=' . $_GET['page'] . '&view=detail&tab=data&id=' . $_GET['id'] . '&edit=1' );
        }

    }

    private function prepareInclude()
    {
        $this->data['tab'] = $this->getGET('tab', 'data');
        $this->data['edit'] = $this->getGET('edit', 0);
        $viewType = "show";
        $view = $this->view;
        if ($view == 'pages.new'){
            $view = 'pages.detail';
        }
        if($this->data['edit'] || $this->view == 'pages.new'){
            $viewType = "edit";
        }

        $this->data['tab_file'] = str_replace('pages.', 'includes.', $view) . '-' . $this->data['tab'] . '-' . $viewType;
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
        }elseif((isset($_GET['action']) || isset($this->data['edit']) && $this->data['edit']) || $this->view == "pages.new"){
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte um diese Seite anzuzeigen.');
            $this->abort();
        }
    }

    private function abort(){
        $this->data['title'] = "Ungenügende Rechte";
        $this->view = "pages.empty";
        $this->render();
    }

    private function requiredFormFields(){
        return [
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
                'spouse' => 'Ehepartner/in',
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
            'bcb_freimitglied' => [
                'gender' => 'Anrede',
                'first_name' => 'Vorname',
                'last_name' => 'Nachname',
                'street' => 'Strasse',
                'zip' => 'PLZ',
                'location' => 'Ort',
                'birthdate' => 'Geburtstag',
            ],
        ];
    }
}