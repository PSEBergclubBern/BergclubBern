<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 17.03.2017
 * Time: 12:31
 */

namespace BergclubPlugin\Stammdaten;


use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;

class MitgliederbeitraegeController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.mitgliederbeitraege';

    protected function first()
    {
        $this->data['title'] = "Mitgliederbeiträge";
        $this->data['mitgliederBeitraege'] = get_option('bcb_mitgliederbeitraege');
    }

    protected function get()
    {

    }

    protected function post()
    {
        foreach($_POST['beitraege'] as $key => $amount){
            $this->data['mitgliederBeitraege'][$key]['amount'] = $amount * 1;
        }

        update_option('bcb_mitgliederbeitraege', $this->data['mitgliederBeitraege'], 'no');

        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Änderungen gespeichert');
    }

    protected function last()
    {

    }

}