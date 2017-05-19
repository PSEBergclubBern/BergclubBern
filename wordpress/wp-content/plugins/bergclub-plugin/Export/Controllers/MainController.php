<?php

namespace BergclubPlugin\Export\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Models\User;

/**
 * Controller for the export page.
 * Adds data to the view and handles requests.
 *
 * @package BergclubPlugin\Export\Controllers
 */
class MainController extends AbstractController
{
    /**
     * @var string
     */
    protected $viewDirectory = __DIR__ . '/../views';
    /**
     * @var string
     */
    protected $view = 'pages.main';

    /**
     * Sets up the common data for every view and request type.
     *
     * @see AbstractController::first()
     */
    protected function first()
    {

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
        if ($user->hasCapability('export_adressen')) {
            $this->data['allowed'][] = "adressen";
        }

        if ($user->hasCapability('export_touren')) {
            $this->data['allowed'][] = "touren";
        }

        if ($user->hasCapability('export_druck')) {
            $this->data['allowed'][] = "druck";
        }
    }

    private function checkRights()
    {
        $currentUser = User::findCurrent();
        if (!$currentUser->hasCapability('export')) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte um diese Seite anzuzeigen.');
            $this->abort();
        }
    }

    private function abort()
    {
        $this->data['title'] = "Ungenügende Rechte";
        $this->view = "pages.empty";
        $this->render();
    }

    /**
     * @see AbstractController::get()
     */
    protected function get()
    {

    }

    /**
     * @see AbstractController::post()
     */
    protected function post()
    {

    }

    /**
     * @see AbstractController::last()
     */
    protected function last()
    {

    }
}