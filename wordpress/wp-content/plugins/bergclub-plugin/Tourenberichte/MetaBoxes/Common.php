<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Tourenberichte\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Common extends MetaBox {
	const TOUREN = '_touren';

	public function getUniqueFieldNames() {
		return array(
			self::TOUREN,
		);
	}


	protected function addAdditionalValuesForView() {

        $args = array(
            'posts_per_page'   => 15,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'touren',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'author_name'	   => '',
            'post_status'      => 'publish',
            'suppress_filters' => true
        );
        $posts_array = get_posts( $args );


        $args1 = array(
            'posts_per_page'   => 15,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'tourenberichte',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'author_name'	   => '',
            'post_status'      => 'draft',// oder lieber publish? --- Ich gehe davon aus, dass in der Drop-Down Liste Touren für die es schon einen Draft Tourenbericht gibt hier nicht auftauchen sollen
            'suppress_filters' => true
        );
        $posts_array_tourenberichte = get_posts( $args1 );

        foreach ($posts_array_tourenberichte as &$post_tourenberichte) {
            $values = get_post_meta($post_tourenberichte->ID, "_touren", true);
            foreach ( $posts_array as &$post_tour ){
                $id = $post_tour->ID;
                if ($values == $id){
                    //remove this entry from array
                    foreach (array_keys($posts_array, $post_tour) as $key) {
                        unset($posts_array[$key]);
                    }
                }
            }
        }

		return array(
			'touren'   => $posts_array,
		);
	}

	public function getUniqueMetaBoxName() {
		return 'commontourenberichte';
	}

	public function getUniqueMetaBoxTitle() {
		return 'Auf welche Tour bezieht sich dieser Tourenbericht? ';
	}

	public function isValid($values, $posttype) {
		$errors = array();
		if ( array_key_exists( self::TOUREN, $values ) ) {
            $tour = $values[self::TOUREN];
			if ( $tour == "" ) {
				$errors[] = 'Tourenbericht muss sich auf eine publizierte Tour beziehen.';
			}
		}

		foreach ( $errors as $errorMsg ) {
			FlashMessage::add( FlashMessage::TYPE_ERROR, $errorMsg );
		}

		return count( $errors ) == 0;
	}

}