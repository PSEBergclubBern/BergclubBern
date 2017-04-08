<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Tourenberichte\MetaBoxes;


use BergclubPlugin\FlashMessage;

class CommonTourenberichte extends MetaBox {
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
            $chosenLeader = $values[self::TOUREN];
			if ( $chosenLeader == "" ) {
				$errors[] = 'Tourenbericht muss sich auf eine publizierte Tour beziehen.';
			}
		}

		foreach ( $errors as $errorMsg ) {
			FlashMessage::add( FlashMessage::TYPE_ERROR, $errorMsg );
		}

		return count( $errors ) == 0;
	}

}