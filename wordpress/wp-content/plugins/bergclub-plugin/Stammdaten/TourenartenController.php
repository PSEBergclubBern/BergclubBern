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

class TourenartenController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.tourenarten';

    protected function first()
    {
        $this->data['title'] = "Tourenarten";
        $this->data['tourenarten'] = get_option('bcb_tourenarten');
    }

    protected function get()
    {
        if ( isset($_GET['action']) && isset($_GET['id']) ){
            if ( $_GET['action']=='delete' && $_GET['id'] ){
                $key = $_GET['id'];

                unset($this->data['tourenarten'][$key]);

                update_option('bcb_tourenarten', $this->data['tourenarten'],'no');
                delete_option($key);

                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourenart gelöscht');
            }
        }
    }

    protected function post()
    {
        if($_POST['new_tourenart']){

            $key = sanitize_title_with_dashes('bcb_' . $_POST['new_tourenart']);
            $this->data['tourenarten'][$key] = $_POST['new_tourenart'];
            update_option('bcb_tourenarten', $this->data['tourenarten'], 'no');

            $arrOptions = [];
            add_option($key, $arrOptions,'', 'no');

            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Hinzufügen erfolgreich');
        }
    }

    protected function last()
    {

    }

}