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

    }

    protected function last(){

    }

    private function getFeedback(){

        $toursToDisplay = null;

        foreach( $this->tours as $tour ){
            if ( (! ( $tour['approved'] || $tour['paid'] || $tour['deleted'] ) ) && $tour['hasFeedback'] ){
                $tour['dateFrom'] = bcb_touren_meta( $tour['id'], 'dateFrom' );
                $toursToDisplay[] = $tour;
            }
        }

        $this->data['tours'] = $toursToDisplay;

    }

    private function getNofeedback(){

        if ( isset( $_GET[ 'id' ] ) ){
            $id = $_GET[ 'id' ];

            $this->data[ 'edit' ] = true;
            $this->data[ 'tour' ] = $this->getTour( $id );

        } else {

            $toursToDisplay = null;

            foreach ($this->tours as $tour) {
                if (!($tour['hasFeedback'] || $tour['approved'] || $tour['paid'] || $tour['deleted'])) {
                    $tour['dateFrom'] = bcb_touren_meta($tour['id'], 'dateFrom');
                    $toursToDisplay[] = $tour;
                }
            }

            $this->data['tours'] = $toursToDisplay;

            $this->data['edit'] = false;

        }

    }

    private function getApproved(){


    }

    private function getPaid(){


    }

    private function updateTourList( $tourList ){

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

            $isInArray = false;
            foreach( $tourList as $existingTour )
                if( $id == $existingTour['id'] ) {
                    $isInArray = true;
                }

            $tourList[] = $this->addElementToTourList( $tour );

        }

        return $tourList;
    }

    private function addElementToTourList( $post ){
        return [
            'id' => $post->ID,
            'leader' => bcb_touren_meta( $post->ID, 'leader' ),
            'title' => get_the_title( $post ),
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
            'deleted' => false,
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
        foreach( $this->tours as $tour ){
            if ( $tour['id'] == $id ){
                return $tour;
            }
            return null;
        }
    }

}