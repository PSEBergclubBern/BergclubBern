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

class SchwierigkeitsgradeController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.schwierigkeitsgrade';

    protected function first()
    {
        $this->data['title'] = "Schwierigkeitsgrade";
        $this->data['tourenarten'] = get_option('bcb_tourenarten');

        if ( isset($_GET['tourid']) ){

            $this->data['tourenartId'] = $_GET['tourid'];

        } else {

            $tourenarten = get_option('bcb_tourenarten');
            reset( $tourenarten );
            $this->data['tourenartId'] = key( $tourenarten );

        }

        $this->data['schwierigkeitsgrade'] = get_option( $this->data['tourenartId'] );
        if (! empty($this->data[ 'tourenarten']) ){
            $this->data['tourenart'] = $this->data[ 'tourenarten' ][ $this->data[ 'tourenartId' ] ];
        } else {
            $this->data['tourenart'] = null;
        }
    }

    protected function get()
    {
        if ( isset ($_GET['tourid'] ) ) {

            $this->data['tourenartId'] = $_GET['tourid'];
            $this->data['schwierigkeitsgrade'] = get_option( $this->data['tourenartId'] );
            $this->data['tourenart'] = $this->data[ 'tourenarten' ][ $this->data[ 'tourenartId' ] ];

            if (isset($_GET['id']) && isset($_GET['action'])) {
                if ($_GET['action'] == 'delete') {
                    $key = $_GET['id'];
                    unset($this->data['schwierigkeitsgrade'][$key]);
                    update_option($this->data['tourenartId'], $this->data['schwierigkeitsgrade'], 'no');
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Schwierigkeit gelöscht');
                }
            }

        }
    }

    protected function post()
    {
        if($_POST['new_schwierigkeitsgrad']){

            $value = trim( $_POST['new_schwierigkeitsgrad'] );
            if ( $value ){
                array_push( $this->data['schwierigkeitsgrade'], $value );
                update_option( $this->data['tourenartId'], $this->data['schwierigkeitsgrade'], 'no');
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Hinzufügen erfolgreich');
            } else {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Hinzufügen nicht erfolgreich');
            }

        }



    }

    protected function last()
    {

    }

}