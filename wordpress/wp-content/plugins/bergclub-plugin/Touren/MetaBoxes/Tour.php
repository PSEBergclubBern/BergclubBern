<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Tour extends MetaBox {

	const TYPE = '_type';
	const REQUIREMENTS_TECHNICAL = '_requirementsTechnical';
	const REQUIREMENTS_CONDITIONAL = '_requirementsConditional';
	const RISE_UP_METERS = '_riseUpMeters';
	const RISE_DOWN_METERS = '_riseDownMeters';
	const DURATION = '_duration';
	const ADDITIONAL_INFO = '_additionalInfo';
	const TRAINING = '_training';
	const JSEVENT = '_jsEvent';
	const PROGRAM = '_program';
	const EQUIPMENT = '_equipment';
	const MAPMATERIAL = '_mapMaterial';
	const ONLINEMAP = '_onlineMap';
	const COSTS = '_costs';
	const COSTS_FOR = '_costsFor';


	public function getUniqueFieldNames() {
		return array(
			self::TYPE,
			self::REQUIREMENTS_TECHNICAL,
			self::REQUIREMENTS_CONDITIONAL,
			self::RISE_UP_METERS,
			self::RISE_DOWN_METERS,
			self::DURATION,
			self::ADDITIONAL_INFO,
			self::TRAINING,
			self::JSEVENT,
			self::PROGRAM,
			self::EQUIPMENT,
			self::MAPMATERIAL,
			self::ONLINEMAP,
			self::COSTS,
			self::COSTS_FOR
		);
	}

	protected function addAdditionalValuesForView() {
		$difficulty = array();
		foreach ( get_option( 'bcb_tourenarten' ) as $key => $tourenart ) {
			$difficulty[ $key ]['options'] = get_option( $key );
			$difficulty[ $key ]['title']   = $tourenart;
		}

		return array(
			'tourenarten'  => get_option( 'bcb_tourenarten' ),
			'conditions'   => array( 1 => 'Leicht', 2 => 'Mittel', 3 => 'Schwer' ),
			'difficulties' => $difficulty,
		);
	}

	public function getUniqueMetaBoxName() {
		return 'tour';
	}

	public function getUniqueMetaBoxTitle() {
		return 'Tourdaten';
	}

	public function isValid( $values ) {
		$errors = array();

		if ( array_key_exists( self::DURATION, $values ) ) {
			$match_format = $this->isValidTime( $values[ self::DURATION ] );
			if ( $match_format === false ) {
				$errors[] = '"Dauer" muss in einem dieser Formate angegeben werden: HH:MM, H:MM';
			}
		}

		if ( array_key_exists( self::ONLINEMAP, $values ) ) {
			if ( ! filter_var( $values[ self::ONLINEMAP ], FILTER_VALIDATE_URL ) && ! filter_var( "http://" . $values[ self::ONLINEMAP ], FILTER_VALIDATE_URL ) ) {
				$errors[] = '"URL Online Route" muss eine gültige URL sein';
			}
		}

		if ( array_key_exists( self::COSTS, $values ) ) {
			if ( ! preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $values[ self::COSTS ]) && ! preg_match("/^-?[0-9]+(?:\,[0-9]{1,2})?$/", $values[ self::COSTS ]) ) {
				$errors[] = '"Kosten (CHF)" muss das Format #(#*).## oder #(#*),## haben';
			}
		}


		foreach ( $errors as $errorMsg ) {
			FlashMessage::add( FlashMessage::TYPE_ERROR, $errorMsg );
		}

		return count( $errors ) == 0;
	}

}