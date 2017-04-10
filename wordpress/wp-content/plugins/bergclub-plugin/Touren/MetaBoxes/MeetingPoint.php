<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class MeetingPoint extends MetaBox {
	const MEETPOINT = '_meetpoint';
	const MEETPOINT_DIFFERENT = '_meetpointDifferent';
	const TIME = '_meetingPointTime';
	const RETURNBACK = '_returnBack';
	const FOOD = '_food';

	public function getUniqueFieldNames() {
		return array(
			self::MEETPOINT,
			self::MEETPOINT_DIFFERENT,
			self::TIME,
			self::RETURNBACK,
			self::FOOD,
		);
	}


	protected function addAdditionalValuesForView() {
		return array(
			'meetingPoints' => array(
				array( 'id' => 1, 'text' => 'Bern HB, Treffpunkt' ),
				array( 'id' => 2, 'text' => 'Bern HB, auf dem Abfahrtsperron' ),
				array( 'id' => 3, 'text' => 'Bern, auf der Welle' ),
				array( 'id' => 99, 'text' => 'Anderer' ),
			),
		);
	}

	public function getUniqueMetaBoxName() {
		return 'meetingpoint';
	}

	public function getUniqueMetaBoxTitle() {
		return 'Treffpunkt';
	}

	public function isValid($values, $posttype) {
		$errors = array();

        if ($posttype != "draft") {
            if (array_key_exists(self::TIME, $values)) {
                $match_format = $this->isValidTime($values[self::TIME]);
                if ($match_format === false) {
                    $errors[] = '"Treffpunkt Zeit" muss in einem dieser Formate angegeben werden: HH:MM, H:MM';
                }
            }
            if (array_key_exists(self::MEETPOINT, $values)) {
                $value = $values[self::MEETPOINT];
                if (strcmp($value, "Anderes") === 0) {
                    if (!array_key_exists(self::MEETPOINT_DIFFERENT, $values)) {
                        $errors[] = 'Wenn als Treffpunkt "Anderes" ausgewählt wurde, muss auch ein alternativer Treffpunkt angegeben werden';
                    }
                }
            }
            if (array_key_exists(self::MEETPOINT_DIFFERENT, $values)) {
                if (!array_key_exists(self::MEETPOINT, $values)) {
                    $errors[] = 'Ein alternativer Treffpunkt kann nur ausgewählt werden, wenn als Treffpunkt "Anderes" ausgewählt wurde';
                } elseif ( ! strcmp( $values[ self::MEETPOINT ], "Anderes" ) === 0 ) {
                    $errors[] = 'Ein alternativer Treffpunkt kann nur ausgewählt werden, wenn als Treffpunkt "Anderes" ausgewählt wurde';
                }
            }
        }

		foreach ( $errors as $errorMsg ) {
			FlashMessage::add( FlashMessage::TYPE_ERROR, $errorMsg );
		}

		return count( $errors ) == 0;
	}
}