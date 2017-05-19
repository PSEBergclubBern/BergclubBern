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

/**
 *  Controls get and post action and adds needed objects to the view for the "Mitgliederbeiträge".
 *
 * @package BergclubPlugin\Stammdaten
 */
class MitgliederbeitraegeController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.mitgliederbeitraege';

    /**
     * Gets the current "Mitgliederbeiträge" from WP options table and adds them to the view.
     * @see AbstractController::first()
     */
    protected function first()
    {
        $this->data['title'] = "Mitgliederbeiträge";
        $this->data['mitgliederBeitraege'] = get_option('bcb_mitgliederbeitraege');
    }

    /**
     * @see AbstractController::get()
     */
    protected function get()
    {

    }

    /**
     *  Handles post requests. Restores the current values for the different "Mitgliederbeiträge" in
     *  WP options table.
     * @see AbstractController::post()
     */
    protected function post()
    {
        foreach ($_POST['beitraege'] as $key => $amount) {
            $this->data['mitgliederBeitraege'][$key]['amount'] = $amount * 1;
        }

        update_option('bcb_mitgliederbeitraege', $this->data['mitgliederBeitraege'], 'no');

        FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Änderungen gespeichert');
    }

    /**
     * @see AbstractController::last()
     */
    protected function last()
    {

    }

}