<?php

namespace BergclubPlugin\Tourenrueckmeldung\Controllers;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\AbstractController;
use BergclubPlugin\MVC\Helpers;
use BergclubPlugin\MVC\Models\Role;
use BergclubPlugin\MVC\Models\User;
use BergclubPlugin\MVC\Models\Option;

class MainController extends AbstractController
{
    protected $viewDirectory = __DIR__ . '/../views';
    protected $view = 'pages.main';
    protected $tours = null;

    protected function first(){

         $this->data['title'] = "Tourenrückmeldungen";

        if(empty($_GET['tab'])){
            $_GET['tab'] = 'nofeedback';
        }
        $this->data['tab'] = $_GET['tab'];
        $this->data['tab_file'] = 'includes.detail-main-' . $this->data['tab'];

        /** get all tours and make sure that they are all in the array */
        $rueckmeldungen = Option::get('bcb_tourenrueckmeldung');
        $rueckmeldungen = $this->updateTourList( $rueckmeldungen );

        $this->tours = $rueckmeldungen;

        $method = $this->getTabMethod('get');

        $this->$method();

    }

    protected function get(){

        if($method = $this->getTabMethod('get')) {
            $this->$method();
        }

    }

    protected function post(){

        if($method = $this->getTabMethod('post')) {
            $this->$method();
        }

    }

    protected function last(){

    }

    private function getNofeedback(){

        if ( isset( $_GET[ 'id' ] ) ){
            $id = $_GET[ 'id' ];

            $this->data[ 'edit' ] = true;
            $this->data[ 'tour' ] = $this->getTour( $id );

        } else {

            $toursToDisplay = null;

            foreach ($this->tours as $tour) {
                if ( !( $tour['hasFeedback'] ) && !( $tour['approved'] ) && !( $tour['paid'] ) ) {
                    $tour['dateFrom'] = bcb_touren_meta($tour['id'], 'dateFrom');
                    $toursToDisplay[] = $tour;
                }
            }

            $this->data['tours'] = $toursToDisplay;
            $this->data['edit'] = false;

        }

    }

    private function getFeedback(){

        if ( isset( $_GET[ 'id' ] ) ){
            $id = $_GET[ 'id' ];

            $this->data[ 'edit' ] = true;
            $this->data[ 'tour' ] = $this->getTour( $id );

        } else {

            $toursToDisplay = null;

            foreach ($this->tours as $tour) {
                if ((!($tour['approved'] || $tour['paid'])) && $tour['hasFeedback']) {
                    $tour['dateFrom'] = bcb_touren_meta($tour['id'], 'dateFrom');
                    $toursToDisplay[] = $tour;
                }
            }

            $this->data['tours'] = $toursToDisplay;
            $this->data['edit'] = false;

        }

    }

    private function getApproved(){

        if ( isset( $_GET[ 'id' ] ) ){
            $id = $_GET[ 'id' ];

            $this->data[ 'edit' ] = true;
            $tour = $this->getTour( $id );

            if ( $tour ){
                $externLeaders = $tour['externLeader'];
                $externLeaders = explode( "\n", $externLeaders );
                $tour['externLeader'] = $externLeaders;

                $participants = $tour['participants'];
                $participants = explode( "\n", $participants );
                $tour['participants'] = $participants;

                $externParticipants = $tour['externParticipants'];
                $externParticipants = explode( "\n", $externParticipants );
                $tour['externParticipants'] = $externParticipants;

                // same for Einzahlung für und Zugunsten von
            }

            $this->data[ 'tour' ] = $tour;

        } else {

            $toursToDisplay = null;

            foreach ($this->tours as $tour) {
                if ( ( !($tour['paid']) ) && $tour['hasFeedback']  && $tour['approved'] ) {
                    $tour['dateFrom'] = bcb_touren_meta($tour['id'], 'dateFrom');
                    $toursToDisplay[] = $tour;
                }
            }

            $this->data['tours'] = $toursToDisplay;
            $this->data['edit'] = false;

        }

    }

    private function postNofeedback(){

        if ( isset( $_GET[ 'id' ] ) ) {
            $id = $_GET['id'];
            $tour = $this->getTour( $id );

            $tour['coLeader'] = $_POST['coLeader'];
            $tour['externLeader'] = $_POST['externLeader'];
            $tour['participants'] = $_POST['participants'];
            $tour['externParticipants'] = $_POST['externParticipants'];
            $tour['executed'] = $_POST['executed'];
            $tour['programDivergence'] = $_POST['programDivergence'];
            $tour['shortReport'] = $_POST['shortReport'];
            $tour['flatCharge'] = $_POST['flatCharge'];
            $tour['tour'] = $_POST['tour'];

            if ( $tour['isSeveralDays'] ) {
                $tour['sleepOver'] = $_POST['sleepOver'];
            }

            $tour['payment'] = $_POST['payment'];
            $tour['inFavor'] = $_POST['inFavor'];
            $tour['iban'] = $_POST['iban'];
            $tour['hasFeedback'] = true;

            // calculate number of participants
            $tour['numberOfParticipants'] = 1;

            if ( $tour['coLeader'] ){
                $tour['numberOfParticipants']++;
            }

            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['externLeader']));
            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['participants']));
            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['externParticipants']));

            $this->updateTour( $tour );

            // send email
            $tourenchefs = User::findByRole( 'bcb_tourenchef' );
            foreach( $tourenchefs as $tourenchef ){
                $email = $tourenchef->email;
                $message = 'Es wurde eine neue Tourenrückmeldung erfasst zur Tour "' . $tour['title'] . '"!';
                if ( $email ){
                    wp_mail( $email, 'Es wurde eine neue Tourenrückmeldung erfasst', $message, '', null );
                }
            }

            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourrückmeldung erfolgreich erfasst.');
            Helpers::redirect( '?page=' . $_GET['page'] . '&tab=nofeedback' );

        }

    }

    private function postFeedback(){

        //check if tour exists

        if ( isset( $_GET[ 'id' ] ) ) {
            $id = $_GET['id'];
            $tour = $this->getTour( $id );

            $tour['coLeader'] = $_POST['coLeader'];
            $tour['externLeader'] = $_POST['externLeader'];
            $tour['participants'] = $_POST['participants'];
            $tour['externParticipants'] = $_POST['externParticipants'];
            $tour['executed'] = $_POST['executed'];
            $tour['programDivergence'] = $_POST['programDivergence'];
            $tour['shortReport'] = $_POST['shortReport'];
            $tour['flatCharge'] = $_POST['flatCharge'];
            $tour['tour'] = $_POST['tour'];

            if ( $tour['isSeveralDays'] ) {
                $tour['sleepOver'] = $_POST['sleepOver'];
            }

            $tour['payment'] = $_POST['payment'];
            $tour['inFavor'] = $_POST['inFavor'];
            $tour['iban'] = $_POST['iban'];
            $tour['approved'] = true;

            // calculate number of participants
            $tour['numberOfParticipants'] = 1;

            if ( $tour['coLeader'] ){
                $tour['numberOfParticipants']++;
            }

            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['externLeader']));
            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['participants']));
            $tour['numberOfParticipants'] = $tour['numberOfParticipants'] + count(explode("\n", $tour['externParticipants']));

            $this->updateTour( $tour );

            // send email
            $kassiers = User::findByRole( 'bcb_kasse' );
            foreach( $kassiers as $kassier ){
                $email = $kassier->email;
                $message = 'Es wurde eine neue Tourenrückmeldung freigegeben zur Tour "' . $tour['title'] . '"!';
                if ( $email ){
                    wp_mail( $email, 'Es wurde eine neue Tourenrückmeldung freigegeben', $message, '', null );
                }
            }

            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourrückmeldung erfolgreich freigegeben.');
            Helpers::redirect( '?page=' . $_GET['page'] . '&tab=feedback' );

        }

    }

    private function postApproved(){

        if ( isset( $_GET[ 'id' ] ) ) {

            $id = $_GET['id'];
            $tour = $this->getTour($id);

            $tour['paid'] = true;

            $this->updateTour( $tour );

            FlashMessage::add(FlashMessage::TYPE_SUCCESS, 'Tourrückmeldung erfolgreich bezahlt.');
            Helpers::redirect( '?page=' . $_GET['page'] . '&tab=approved' );

        }

    }

    private function updateTourList( $tourList ){

        if(!is_array($tourList)){
            $tourList = [];
        }

        $allTours = get_posts([
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

        foreach( $allTours as $tour ){

            $id = $tour->ID;

            if(!isset($tourList[$id])) {
                $tourList[$id] = $this->addElementToTourList($tour);
            }
        }

        return $tourList;
    }

    private function addElementToTourList( $post ){
        return [
            'id' => $post->ID,
            'leader' => bcb_touren_meta( $post->ID, 'leader' ),
            'title' => get_the_title( $post ),
            'isSeveralDays' => bcb_touren_meta( $post->ID, 'isSeveralDays'),
            'coLeader' => null,
            'externLeader' => null,
            'participants' => null,
            'externParticipants' => null,
            'executed' => false,
            'programDivergence' => null,
            'shortReport' => null,
            'flatCharge' => null,
            'tour' => null,
            'sleepOver' => null,
            'payment' => null,
            'inFavor' => null,
            'iban' => null,
            'numberOfParticipants' => null,
            'hasFeedback' => false,
            'approved' => false,
            'paid' => false,
        ];
    }

    private function getTabMethod($requestType){
        if(isset($this->data['tab']) && !empty($this->data['tab'])) {
            $method = strtolower($requestType) . strtoupper(substr($this->data['tab'], 0, 1)) . substr($this->data['tab'], 1);
            if (method_exists($this, $method)) {
                return $method;
            }
        }

        return false;
    }

    private function getTour( $id ){
        if(isset($this->tours[$id])){
            return $this->tours[$id];
        }
        return null;
    }

    private function updateTour( $updatedTour ){
        $this->tours[$updatedTour['id']] = $updatedTour;
        Option::set('bcb_tourenrueckmeldung', $this->tours);
    }

}