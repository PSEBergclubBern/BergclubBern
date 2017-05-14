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

        $quarterTouren = [
            [
                'from' => date('Y') . '-04-01',
                'to' => date('Y') . '-06-30',
            ],
            [
                'from' => date('Y') . '-07-01',
                'to' => date('Y') . '-09-30',
            ],
            [
                'from' => date('Y') . '-07-01',
                'to' => date('Y') . '-12-31',
            ],
            [
                'from' => (date('Y') + 1) . '-01-01',
                'to' => (date('Y') + 1) . '-03-31',
            ],
        ];

        $quarterRueckblick = [
            [
                'from' => date('Y') . '-01-01',
                'to' => date('Y') . '-03-31',
            ],
            [
                'from' => date('Y') . '-04-01',
                'to' => date('Y') . '-06-30',
            ],
            [
                'from' => date('Y') . '-07-01',
                'to' => date('Y') . '-09-30',
            ],
            [
                'from' => date('Y') . '-07-01',
                'to' => date('Y') . '-12-31',
            ],
        ];

        $this->checkRights();

        $this->view = 'pages.export';
        $this->data['title'] = "Export";

        $this->data['quarterTouren'] = $quarterTouren[(int)(ceil(date('n') / 3) - 1)];
        $this->data['quarterRueckblick'] = $quarterRueckblick[(int)(ceil(date('n') / 3) - 1)];

        $this->data['allowed'] = [];

        $user = User::findCurrent();
        if($user->hasCapability('export_adressen')){
            $this->data['allowed'][] = "adressen";
        }

        if($user->hasCapability('export_touren')){
            $this->data['allowed'][] = "touren";
        }

        if($user->hasCapability('export_druck')){
            $this->data['allowed'][] = "druck";
        }
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