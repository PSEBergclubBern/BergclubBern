<?php

namespace BergclubPlugin\Export\Controllers;

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
        $this->view = 'pages.export';
        $this->data['title'] = "Export";
        $this->checkRights();
    }

    protected function get(){

    }

    protected function post(){

    }

    protected function last(){

    }

    private function checkRights(){
        $currentUser = User::findCurrent();
        if(!$currentUser->hasCapability('export')){
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