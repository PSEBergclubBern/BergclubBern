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
 *  Controls get and post action and adds needed objects to the view for the "Schwierigkeitsgrade".
 * @package BergclubPlugin\Stammdaten
 */
class SchwierigkeitsgradeController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.schwierigkeitsgrade';

    /**
     *  Adds the "Tourenarten" for the dropdown menu to the view. Adds the "Schwierigkeitsgrade" for the specified
     *  "Tourenart" to the view as well (depending on parameter "tourid").
     * @see AbstractController::first()
     */
    protected function first()
    {
        $this->data['title'] = "Schwierigkeitsgrade";
        $this->data['tourenarten'] = get_option('bcb_tourenarten');

        if (isset($_GET['tourid'])) {

            $this->data['tourenartId'] = $_GET['tourid'];

        } else {

            // if no "tourid" is specified, choose first "Tourenart" in WP options table.
            $tourenarten = get_option('bcb_tourenarten');
            reset($tourenarten);
            $this->data['tourenartId'] = key($tourenarten);

        }

        // add "Schwierigkeitsgrade" for the selected "Tourenart"
        $this->data['schwierigkeitsgrade'] = get_option($this->data['tourenartId']);

        if (!empty($this->data['tourenarten'])) {
            $this->data['tourenart'] = $this->data['tourenarten'][$this->data['tourenartId']];
        } else {
            $this->data['tourenart'] = null;
        }
    }

    /**
     *  Handles get requests. Displays the "Schwierigkeitsgrade" for the currently selected "Tourenart".
     *  Deletes the specified "Schwierigkeitsgrad" if necessary.
     * @see AbstractController::get()
     */
    protected function get()
    {
        if (isset ($_GET['tourid'])) {

            // add data for the currently selected "Tourenart" to the view
            $this->data['tourenartId'] = $_GET['tourid'];
            $this->data['schwierigkeitsgrade'] = get_option($this->data['tourenartId']);
            $this->data['tourenart'] = $this->data['tourenarten'][$this->data['tourenartId']];

            // check if a "Schwierigkeitsgrad" should be removed
            if (isset($_GET['id']) && isset($_GET['action'])) {
                if ($_GET['action'] == 'delete') {
                    $key = $_GET['id'];
                    unset($this->data['schwierigkeitsgrade'][$key]);

                    // restore the "Schwierigkeitsgrade" in WP options table
                    update_option($this->data['tourenartId'], $this->data['schwierigkeitsgrade'], 'no');
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Schwierigkeit gelöscht');
                }
            }

        }
    }

    /**
     *  Handles post requests. Adds a new "Schwierigkeitsgrad" to the currently selected "Tourenart" and stores
     *  it in WP options table.
     * @see AbstractController::post()
     */
    protected function post()
    {
        if ($_POST['new_schwierigkeitsgrad']) {

            $value = trim($_POST['new_schwierigkeitsgrad']);
            if ($value) {
                array_push($this->data['schwierigkeitsgrade'], $value);
                update_option($this->data['tourenartId'], $this->data['schwierigkeitsgrade'], 'no');
                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Hinzufügen erfolgreich');
            } else {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Hinzufügen nicht erfolgreich');
            }

        }

    }

    /**
     * @see AbstractController::last()
     */
    protected function last()
    {

    }

}