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
	const LEADER = '_leader';

	public function getUniqueFieldNames() {
		return array(
			self::LEADER,
		);
	}


	protected function addAdditionalValuesForView() {

        $args = array(
            'posts_per_page'   => 5,
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
			'leiter'   => $posts_array,
			'coLeiter' => get_users(),
			'signUpTo' => get_users(),
		);
	}

	public function getUniqueMetaBoxName() {
		return 'common';
	}

	public function getUniqueMetaBoxTitle() {
		return 'Zusatzinformationen';
	}

	public function isValid($values, $posttype) {
		$errors = array();
		if ( array_key_exists( self::LEADER, $values ) ) {
            $chosenLeader = $values[self::LEADER];
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