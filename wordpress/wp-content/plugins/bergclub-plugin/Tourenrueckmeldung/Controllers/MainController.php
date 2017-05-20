<?php

namespace BergclubPlugin\Tourenrueckmeldung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

/**
 *  Controls get and post actions and needed objects for the different views for the admin page "Rückmeldungen"
 * @package BergclubPlugin\Tourenrueckmeldung\Controllers
 */
class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';
    protected $tours = null;
    protected $rueckmeldungen = null;
    /* @var User $user */
    protected $user = null;
    protected $id = 0;

    protected $readOthers = false;
    protected $readBCB = false;
    protected $readJugend = false;
    protected $editOthers = false;
    protected $editBCB = false;
    protected $editJugend = false;

    /**
     * Checks the rights (and the therefore displayed tabs) for the current user and adds the needed data to the view.
     * @see AbstractController::first()
     */
    protected function first()
    {

        $this->data['tabs'] = [];

        $this->user = User::findCurrent();

        if ($this->user->hasCapability('rueckmeldungen_tab_nofeedback')) {
            $this->data['tabs']['nofeedback'] = 'Neue Rückmeldung';
        }

        if ($this->user->hasCapability('rueckmeldungen_tab_feedback')) {
            $this->data['tabs']['feedback'] = 'Zur Freigabe';
        }

        if ($this->user->hasCapability('rueckmeldungen_tab_approved')) {
            $this->data['tabs']['approved'] = 'Zur Auszahlung';
        }

        if ($this->user->hasCapability('rueckmeldungen_tab_all')) {
            $this->data['tabs']['all'] = 'Alle Rückmeldungen';
        }

        if (empty($this->data['tabs'])) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte.');
            $this->view = "pages.empty";
        } else {

            if ($this->user->hasCapability('rueckmeldungen_read_others') || $this->user->hasCapability('rueckmeldungen_jugend_read_others')) {
                $this->readOthers = true;
            }

            if ($this->user->hasCapability('rueckmeldungen_read')) {
                $this->readBCB = true;
            }

            if ($this->user->hasCapability('rueckmeldungen_jugend_read')) {
                $this->readJugend = true;
            }

            if ($this->user->hasCapability('rueckmeldungen_edit_others') || $this->user->hasCapability('rueckmeldungen_jugend_edit_others')) {
                $this->editOthers = true;
            }

            if ($this->user->hasCapability('rueckmeldungen_edit')) {
                $this->editBCB = true;
            }

            if ($this->user->hasCapability('rueckmeldungen_jugend_edit')) {
                $this->editJugend = true;
            }

            $this->rueckmeldungen = Option::get('rueckmeldungen');
            if (!$this->rueckmeldungen) {
                $this->rueckmeldungen = [];
            }

            $this->data['title'] = "Tourenrückmeldungen";

            if (empty($_GET['tab'])) {
                reset($this->data['tabs']);
                $_GET['tab'] = key($this->data['tabs']);
            }

            if (empty($_GET['id'])) {
                $_GET['id'] = 0;
            }

            $this->id = $_GET['id'];
            $this->data['tab'] = $_GET['tab'];
            $this->data['tab_file'] = 'includes.detail-main-' . $this->data['tab'];
        }
    }

    /**
     * Handles GET request type.
     * <p>
     * Checks if a tab is active and if a specific method for this tab exists, if yes the specific method is called.
     *
     * @see AbstractController::get()
     */
    protected function get()
    {

        if ($method = $this->getTabMethod('get')) {
            $this->$method();
        }

    }

    private function getTabMethod($requestType)
    {
        if (isset($this->data['tab']) && !empty($this->data['tab'])) {
            $method = strtolower($requestType) . strtoupper(substr($this->data['tab'], 0, 1)) . substr($this->data['tab'], 1);
            if (method_exists($this, $method)) {
                return $method;
            }
        }

        return false;
    }

    /**
     * Handles POST request type.
     * <p>
     * Checks if a tab is active and if a specific method for this tab exists, if yes the specific method is called.
     *
     * @see AbstractController::post()
     */
    protected function post()
    {

        if ($method = $this->getTabMethod('post')) {
            $this->$method();
        }

    }

    /**
     * @see AbstractController::last()
     */
    protected function last()
    {

    }

    /**
     * Handles get request for "Neue Rückmeldung" tab.
     */
    private function getNofeedback()
    {

        // if id is not set, load all "Rückmeldungen" without feedback
        if (!$this->id) {
            $this->data['showDateFeedback'] = false;
            $this->data['showDateApproved'] = false;
            $this->data['showDatePay'] = false;
            $this->data['edit'] = false;
            $tours = get_posts([
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type' => 'touren',
                'order' => 'DESC',
                'orderby' => '_dateToDB',
                'meta_query' => [
                    [
                        'key' => '_dateToDB',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE',
                        'compare' => '<',
                    ],
                ],
            ]);

            $this->data['tours'] = [];

            foreach ($tours as $tour) {
                if (empty($this->rueckmeldungen[$tour->ID])) {
                    $leaderId = get_post_meta($tour->ID, '_leader', true);
                    $coLeaderId = get_post_meta($tour->ID, '_coLeader', true);
                    if ($this->readOthers || $leaderId == $this->user->ID || $coLeaderId == $this->user->ID) {
                        $isYouth = get_post_meta($tour->ID, '_isYouth', true);
                        if ($this->editOthers || $leaderId == $this->user->ID || $coLeaderId == $this->user->ID) {
                            if (($isYouth == 0 && $this->editBCB) || ($isYouth == 1 && $this->editJugend) || ($isYouth == 2 && ($this->editBCB || $this->readJugend))) {
                                $this->data['tours'][$tour->ID] = [
                                    'date' => bcb_touren_meta($tour->ID, 'dateDisplayFull'),
                                    'title' => $tour->post_title,
                                    'leader' => bcb_touren_meta($tour->ID, 'leader'),
                                    'coLeader' => bcb_touren_meta($tour->ID, 'coLeader'),
                                ];
                            }
                        }
                    }
                }
            }
        } else {
            // load form to record feedback for the specified "Rückmeldung"
            $this->data['edit'] = true;
            $this->loadRueckmeldung();
        }
    }

    /**
     *  Loads the "Rückmeldung" with the currently stored id
     */
    private function loadRueckmeldung()
    {
        $leaderId = get_post_meta($this->id, '_leader', true);
        $coLeaderId = get_post_meta($this->id, '_coLeader', true);
        $isYouth = get_post_meta($this->id, '_isYouth', true);
        $edit = false;

        // check if current user has caps to edit
        if ($_GET['tab'] == 'nofeedback' || $_GET['tab'] == 'all') {
            $edit = true;
        } elseif ($_GET['tab'] == 'feedback') {
            if (($isYouth == 0 && $this->user->hasCapability('rueckmeldungen_publish'))
                || ($isYouth == 1 && $this->user->hasCapability('rueckmeldungen_jugend_publish'))
                || ($isYouth == 2 && ($this->user->hasCapability('rueckmeldungen_publish') || $this->user->hasCapability('rueckmeldungen_jugend_publish')))
            ) {
                $edit = true;
            }
        } elseif ($_GET['tab'] == 'approved' && $this->user->hasCapability('rueckmeldungen_pay')) {
            $edit = true;
        }

        if ($edit && $_GET['tab'] != 'approved') {
            if ($this->editOthers || $leaderId == $this->user->ID || $coLeaderId == $this->user->ID) {
                if (($isYouth == 0 && $this->editBCB) || ($isYouth == 1 && $this->editJugend) || ($isYouth == 2 && ($this->editBCB || $this->readJugend))) {
                    $edit = true;
                }
            }
        }

        if (!$edit) {
            $this->view = "pages.empty";
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Sie haben nicht die benötigten Rechte.');
        } else {
            // check if "Rückmeldung" has already been recorded
            if (!isset($this->rueckmeldungen[$this->id])) {
                if ($_GET['tab'] != "nofeedback") {
                    FlashMessage::add(FlashMessage::TYPE_ERROR, 'Keine Rückmeldung für Tour mit Id ' . $this->id . ' gefunden.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&view=' . $_GET['view'] . '&tab=' . $_GET['tab']);
                }

                $tour = get_post($this->id);

                if (!$tour) {
                    FlashMessage::add(FlashMessage::TYPE_ERROR, 'Keine Tour mit Id ' . $this->id . ' gefunden.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&view=' . $_GET['view'] . '&tab=' . $_GET['tab']);
                }

                $this->data['rueckmeldung'] = [
                    'executed' => 1,
                    'title' => $tour->post_title,
                    'leader' => bcb_touren_meta($this->id, 'leader'),
                    'coLeader' => bcb_touren_meta($this->id, 'coLeader'),
                    'externLeader' => '',
                    'participants' => '',
                    'externParticipants' => '',
                    'programDivergence' => '',
                    'shortReport' => '',
                    'flatCharge' => '',
                    'journey' => '',
                    'isSeveralDays' => bcb_touren_meta($this->id, 'isSeveralDays'),
                    'sleepOver' => '',
                    'paymentIsForLeader' => true,
                    'date' => bcb_touren_meta($this->id, 'dateDisplayFull'),
                ];
            } else {
                $rueckmeldung = $this->rueckmeldungen[$this->id];
                if ($_GET['tab'] == "feedback" && $rueckmeldung['state'] > 1) {
                    FlashMessage::add(FlashMessage::TYPE_ERROR, 'Diese Rückmeldung wurde bereits freigegeben.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&tab=' . $_GET['tab']);
                } elseif ($_GET['tab'] == "approved" && $rueckmeldung['state'] > 2) {
                    FlashMessage::add(FlashMessage::TYPE_ERROR, 'Diese Rückmeldung wurde bereits ausbezahlt.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&tab=' . $_GET['tab']);
                }
                $this->data['rueckmeldung'] = $rueckmeldung;
            }
        }
    }

    /**
     * Handles post request for "Neue Rückmeldung" tab. Stores a new "Rückmeldung".
     */
    private function postNofeedback()
    {

        if ($this->id > 0) {
            $this->data['edit'] = true;

            $rueckmeldung = array_map('trim', $_POST);
            $tour = get_post($this->id);
            if ($tour) {
                $rueckmeldung['leader'] = bcb_touren_meta($this->id, 'leader');
                $rueckmeldung['coLeader'] = bcb_touren_meta($this->id, 'coLeader');
                $rueckmeldung['title'] = $tour->post_title;
                $rueckmeldung['date'] = bcb_touren_meta($this->id, 'dateDisplayFull');
                $rueckmeldung['isSeveralDays'] = bcb_touren_meta($this->id, 'isSeveralDays');
                $rueckmeldung['dateFeedback'] = date('d.m.Y H:i:s');

                if ($this->saveRueckmeldung($rueckmeldung)) {
                    $isYouth = bcb_touren_meta($this->id, 'isYouth');
                    $role = 'tourenchef';
                    if ($isYouth == 1) {
                        $role = 'tourenchef_jugend';
                    }

                    $this->mail($role, 'Neue Tourenrückmeldung', 'Für die Tour "' . $rueckmeldung['title'] . '" wurde eine Rückmeldung erfasst. Bitte prüfen Sie die Rückmeldung unter ' . admin_url() . '.');
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourrückmeldung erfolgreich erfasst.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&tab=nofeedback');
                }
            } else {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Die Tour mit der Id ' . $this->id . ' konnte nicht gefunden werden.');
                Helpers::redirect('?page=' . $_GET['page'] . '&tab=nofeedback');
            }
        }

    }

    private function saveRueckmeldung($rueckmeldung)
    {
        $errors = [];

        $this->addNumberOfParticipants($rueckmeldung);
        $rueckmeldung['flatCharge'] = number_format($rueckmeldung['flatCharge'] * 1, 2, '.', '');
        $rueckmeldung['journey'] = number_format($rueckmeldung['journey'] * 1, 2, '.', '');
        if (!isset($rueckmeldung['sleepOver'])) {
            $rueckmeldung['sleepOver'] = 0;
        }
        $rueckmeldung['sleepOver'] = number_format($rueckmeldung['sleepOver'] * 1, 2, '.', '');

        // validate "Rückmeldung"
        if ($rueckmeldung['executed']) {
            if ($this->countLines($rueckmeldung['participants']) + $this->countLines($rueckmeldung['externParticipants']) == 0) {
                $errors[] = "Teilnehmer BCB/Externe Teilnehmer (es muss mindestens ein Teilnehmer erfasst sein)";
            }
            if (empty($rueckmeldung['shortReport'])) {
                $errors[] = "Kurzbericht muss erfasst sein";
            }
        }

        if ($rueckmeldung['flatCharge'] == 0) {
            $errors[] = "Pauschale (muss grösser als 0 sein)";
        }

        if (!empty($errors)) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, 'Folgende Felder müssen ausgefüllt werden:<br/>- ' . join('<br/>- ', $errors));
            $this->data['rueckmeldung'] = $rueckmeldung;
            return false;
        }

        if (!isset($rueckmeldung['state'])) {
            $rueckmeldung['state'] = 1;
        } else {
            $rueckmeldung['state']++;
        }

        $this->rueckmeldungen[$this->id] = $rueckmeldung;
        // store "Rückmeldungen" in WP options table
        Option::set('rueckmeldungen', $this->rueckmeldungen);

        return true;
    }

    /**
     * Adds the number of participants to the "Rückmeldung" $rueckmeldung
     */
    private function addNumberOfParticipants(&$rueckmeldung)
    {
        if (!$rueckmeldung['executed']) {
            $numberOfParticipants = 0;
        } else {
            // every tour has a leader and therefore at least one participant
            $numberOfParticipants = 1;

            if (!empty($rueckmeldung['coLeader'])) {
                $numberOfParticipants++;
            }

            $numberOfParticipants += $this->countLines($rueckmeldung['externLeader']);
            $numberOfParticipants += $this->countLines($rueckmeldung['participants']);
            $numberOfParticipants += $this->countLines($rueckmeldung['externParticipants']);
        }
        $rueckmeldung['numberOfParticipants'] = $numberOfParticipants;
    }

    /**
     * returns the number of lines $text has.
     */
    private function countLines($text)
    {
        $numLines = 0;
        $text = str_replace("\r", "", $text);
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $numLines++;
            }
        }
        return $numLines;
    }

    /**
     * Sends a mail to each user who has the role $role with the specified subject and message.
     */
    private function mail($role, $subject, $message)
    {
        $users = User::findByRole($role);
        foreach ($users as $user) {
            if ($user->email) {
                wp_mail($user->email, $subject, $message);
            }
        }
    }

    /**
     * Handles get request for "Zur Freigabe" tab.
     */
    private function getFeedback()
    {
        // if id is not set, load all "Rückmeldungen" with feedback who aren't approved yet.
        if (!$this->id) {
            $this->data['showDateFeedback'] = true;
            $this->data['showDateApproved'] = false;
            $this->data['showDatePay'] = false;
            $this->data['edit'] = false;
            $tours = get_posts([
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type' => 'touren',
                'order' => 'DESC',
                'orderby' => '_dateToDB',
                'meta_query' => [
                    [
                        'key' => '_dateToDB',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE',
                        'compare' => '<',
                    ],
                ],
            ]);

            $this->data['tours'] = [];

            foreach ($tours as $tour) {
                if (!empty($this->rueckmeldungen[$tour->ID]) && $this->rueckmeldungen[$tour->ID]['state'] == 1) {
                    $rueckmeldung = $this->rueckmeldungen[$tour->ID];
                    $isYouth = bcb_touren_meta($tour->ID, 'isYouth');
                    if ((($isYouth == 0 || $isYouth = 2) && $this->user->hasCapability('rueckmeldungen_publish')) || ($isYouth == 1 && $this->user->hasCapability('rueckmeldungen_jugend_publish'))) {
                        $this->data['tours'][$tour->ID] = [
                            'date' => $rueckmeldung['date'],
                            'dateFeedback' => $rueckmeldung['dateFeedback'],
                            'title' => $rueckmeldung['title'],
                            'leader' => bcb_touren_meta($tour->ID, 'leader'),
                            'coLeader' => bcb_touren_meta($tour->ID, 'coLeader'),
                        ];
                    }
                }
            }
        } else {
            // load form to approve the specified "Rückmeldung"
            $this->data['edit'] = true;
            $this->loadRueckmeldung();
        }
    }

    /**
     * Handles post request for "Zur Freigabe" tab. Stores an existing (and maybe updated) "Rückmeldung".
     */
    private function postFeedback()
    {
        if ($this->id > 0) {
            $this->data['edit'] = true;
            $this->loadRueckmeldung();

            $rueckmeldung = array_merge($this->data['rueckmeldung'], array_map('trim', $_POST));
            $rueckmeldung['leader'] = bcb_touren_meta($this->id, 'leader');
            $rueckmeldung['coLeader'] = bcb_touren_meta($this->id, 'coLeader');

            if ($rueckmeldung['state'] > 1) {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Diese Rückmeldung wurde bereits freigegeben.');
                Helpers::redirect('?page=' . $_GET['page'] . '&tab=' . $_GET['tab']);
            }

            $tour = get_post($this->id);
            if ($tour) {
                $rueckmeldung['dateApproved'] = date('d.m.Y H:i:s');

                if ($this->saveRueckmeldung($rueckmeldung)) {
                    $this->mail('kasse', 'Neue Spesenabrechnung', 'Für die Tour "' . $rueckmeldung['title'] . '" wurde eine Spesenabrechnung erfasst. Bitte prüfen Sie diese unter ' . admin_url() . '.');
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourrückmeldung erfolgreich freigegeben.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&tab=feedback');
                }
            } else {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Die Tour mit der Id ' . $this->id . ' konnte nicht gefunden werden.');
                Helpers::redirect('?page=' . $_GET['page'] . '&tab=feedback');
            }
        }
    }

    /**
     * Handles get request for "Zur Auszahlung" tab.
     */
    private function getApproved()
    {
        // if no id is specified all "Rückmeldungen" who aren't paid are displayed
        if (!$this->id) {
            $this->data['showDateFeedback'] = true;
            $this->data['showDateApproved'] = true;
            $this->data['showDatePay'] = false;
            $this->data['edit'] = false;
            $tours = get_posts([
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type' => 'touren',
                'order' => 'DESC',
                'orderby' => '_dateToDB',
                'meta_query' => [
                    [
                        'key' => '_dateToDB',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE',
                        'compare' => '<',
                    ],
                ],
            ]);

            $this->data['tours'] = [];

            foreach ($tours as $tour) {
                if (!empty($this->rueckmeldungen[$tour->ID]) && $this->rueckmeldungen[$tour->ID]['state'] == 2 && $this->user->hasCapability('rueckmeldungen_pay')) {
                    $rueckmeldung = $this->rueckmeldungen[$tour->ID];
                    $this->data['tours'][$tour->ID] = [
                        'date' => $rueckmeldung['date'],
                        'dateFeedback' => $rueckmeldung['dateFeedback'],
                        'dateApproved' => $rueckmeldung['dateApproved'],
                        'title' => $rueckmeldung['title'],
                        'leader' => bcb_touren_meta($tour->ID, 'leader'),
                        'coLeader' => bcb_touren_meta($tour->ID, 'coLeader'),
                    ];
                }
            }
        } else {
            // display "Rückmeldung" who will be paid
            $this->data['edit'] = true;
            $this->loadRueckmeldung();
        }

    }

    /**
     * Handles post request for "Zur Auszahlung" tab. Marks a "Rückmeldung" as paid and therefore finished.
     */
    private function postApproved()
    {
        if ($this->id > 0) {
            $this->data['edit'] = true;
            $this->loadRueckmeldung();

            $rueckmeldung = $this->data['rueckmeldung'];
            $rueckmeldung['leader'] = bcb_touren_meta($this->id, 'leader');
            $rueckmeldung['coLeader'] = bcb_touren_meta($this->id, 'coLeader');

            if ($rueckmeldung['state'] > 2) {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Diese Rückmeldung wurde bereits ausbezahlt.');
                Helpers::redirect('?page=' . $_GET['page'] . '&tab=' . $_GET['tab']);
            }

            $tour = get_post($this->id);
            if ($tour) {
                $rueckmeldung['datePay'] = date('d.m.Y H:i:s');

                if ($this->saveRueckmeldung($rueckmeldung)) {
                    FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Auszahlung gespeichert.');
                    Helpers::redirect('?page=' . $_GET['page'] . '&tab=approved');
                }
            } else {
                FlashMessage::add(FlashMessage::TYPE_ERROR, 'Die Tour mit der Id ' . $this->id . ' konnte nicht gefunden werden.');
                Helpers::redirect('?page=' . $_GET['page'] . '&tab=approved');
            }
        }

    }

    /**
     * Handles get request for "Alle Rückmeldungen" tab.
     */
    private function getAll()
    {

        if (!$this->id) {
            $this->data['showDateFeedback'] = true;
            $this->data['showDateApproved'] = true;
            $this->data['showDatePay'] = true;
            $this->data['edit'] = false;
            $tours = get_posts([
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type' => 'touren',
                'order' => 'DESC',
                'orderby' => '_dateToDB',
                'meta_query' => [
                    [
                        'key' => '_dateToDB',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE',
                        'compare' => '<',
                    ],
                ],
            ]);

            $this->data['tours'] = [];

            foreach ($tours as $tour) {
                if (!empty($this->rueckmeldungen[$tour->ID])) {
                    $leaderId = get_post_meta($tour->ID, '_leader', true);
                    $coLeaderId = get_post_meta($tour->ID, '_coLeader', true);
                    if ($this->readOthers || $leaderId == $this->user->ID || $coLeaderId == $this->user->ID) {
                        $rueckmeldung = $this->rueckmeldungen[$tour->ID];

                        $dateFeedback = "&nbsp;";
                        $dateApproved = "&nbsp;";
                        $datePay = "&nbsp;";
                        if (!empty($rueckmeldung['dateFeedback'])) {
                            $dateFeedback = $rueckmeldung['dateFeedback'];
                        }
                        if (!empty($rueckmeldung['dateApproved'])) {
                            $dateApproved = $rueckmeldung['dateApproved'];
                        }
                        if (!empty($rueckmeldung['datePay'])) {
                            $datePay = $rueckmeldung['datePay'];
                        }

                        $this->data['tours'][$tour->ID] = [
                            'date' => $rueckmeldung['date'],
                            'dateFeedback' => $dateFeedback,
                            'dateApproved' => $dateApproved,
                            'datePay' => $datePay,
                            'title' => $rueckmeldung['title'],
                            'leader' => bcb_touren_meta($tour->ID, 'leader'),
                            'coLeader' => bcb_touren_meta($tour->ID, 'coLeader'),
                        ];
                    }
                }
            }
        } else {
            $this->loadRueckmeldung();
            $rueckmeldung = $this->data['rueckmeldung'];
            $allowEdit = false;
            $leaderId = get_post_meta($this->id, '_leader', true);
            $coLeaderId = get_post_meta($this->id, '_coLeader', true);
            $isYouth = get_post_meta($this->id, '_isYouth', true);
            if ($this->editOthers || $leaderId == $this->user->ID || $coLeaderId == $this->user->ID) {
                if (($isYouth == 0 && $this->editBCB) || ($isYouth == 1 && $this->editJugend) || ($isYouth == 2 && ($this->editBCB || $this->readJugend))) {
                    if ($this->user->hasRole('redaktion')) {
                        $allowEdit = true;
                    }
                }
            }
            $this->data['allowEdit'] = $allowEdit;
            $this->data['edit'] = true;
        }

    }
}