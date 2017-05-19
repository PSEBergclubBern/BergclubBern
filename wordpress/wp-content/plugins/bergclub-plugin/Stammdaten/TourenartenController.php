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
 *  Controls get and post action and adds needed objects to the view for the "Tourenarten".
 * @package BergclubPlugin\Stammdaten
 */
class TourenartenController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/views';
    protected $view = 'pages.tourenarten';

    /**
     * Gets the currently stored "Tourenarten" from WP options table and adds them to the view.
     * @see AbstractController::first()
     */
    protected function first()
    {
        $this->data['title'] = "Tourenarten";
        $this->data['tourenarten'] = get_option('bcb_tourenarten');
    }

    /**
     *  Handles get requests. Deletes the specified "Tourenart" if parameters "id" and "action" are set.
     * @see AbstractController::get()
     */
    protected function get()
    {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            if ($_GET['action'] == 'delete' && $_GET['id']) {
                $key = $_GET['id'];

                unset($this->data['tourenarten'][$key]);

                // restore "Tourenarten" in WP options table without the deleted "Tourenart"
                update_option('bcb_tourenarten', $this->data['tourenarten'], 'no');
                // delete "Schwierigkeitsgrade" for the deleted "Tourenart"
                delete_option($key);

                FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourenart gelöscht');
            }
        }
    }


    /**
     *  Handles post requests. Adds a new "Tourenart" to the WP options table if a new one is recorded.
     * @see AbstractController::post()
     */
    protected function post()
    {
        if ($_POST['new_tourenart']) {

            $key = sanitize_title_with_dashes('bcb_' . $_POST['new_tourenart']);
            $this->data['tourenarten'][$key] = $_POST['new_tourenart'];
            update_option('bcb_tourenarten', $this->data['tourenarten'], 'no');

            // add a new (empty) option for the "Schwierigkeitsgrade" of the new "Tourenart"
            add_option($key, [], '', 'no');

            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Hinzufügen erfolgreich');
        }
    }

    /**
     * @see AbstractController::last()
     */
    protected function last()
    {

    }

}