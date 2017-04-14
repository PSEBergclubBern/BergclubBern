<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Touren\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Common extends MetaBox {
	const DATE_FROM_IDENTIFIER = '_dateFrom';
	const DATE_TO_IDENTIFIER = '_dateTo';
	const DATE_FROM_DB = '_dateFromDB';
	const DATE_TO_DB = '_dateToDB';
	const LEADER = '_leader';
	const CO_LEADER = '_coLeader';
	const SIGNUP_UNTIL = '_signupUntil';
	const SIGNUP_TO = '_signupTo';
	const SLEEPOVER = '_sleepOver';

	public function getUniqueFieldNames() {
		return array(
			self::DATE_FROM_IDENTIFIER,
			self::DATE_TO_IDENTIFIER,
			self::DATE_FROM_DB,
			self::DATE_TO_DB,
			self::LEADER,
			self::CO_LEADER,
			self::SIGNUP_UNTIL,
			self::SIGNUP_TO,
			self::SLEEPOVER,
		);
	}

    /**
     * Logic for saving the date in an sortable manner
     *
     * @param $values
     * @return array
     */
    protected function preSave($values)
    {
        $values = parent::preSave($values);

        $values[self::DATE_FROM_DB] = null;
        $values[self::DATE_TO_DB] = null;

        if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values) && !empty($values[self::DATE_FROM_IDENTIFIER])) {
            $date_from = \DateTime::createFromFormat( "d.m.Y", $values[ self::DATE_FROM_IDENTIFIER ] );
            if ($date_from !== false) {
                $values[self::DATE_FROM_DB] = $date_from->format('Y-m-d');
            }
        }

        $values[self::DATE_TO_DB] = null;
        if (array_key_exists(self::DATE_TO_IDENTIFIER, $values) && !empty($values[self::DATE_TO_IDENTIFIER])) {
            $date_to = \DateTime::createFromFormat( "d.m.Y", $values[ self::DATE_TO_IDENTIFIER ] );
            if ($date_to !== false) {
                $values[self::DATE_TO_DB] = $date_to->format('Y-m-d');
            }
        }

        return $values;
    }


    protected function addAdditionalValuesForView() {
		$roles = wp_get_current_user()->roles;
		if ( in_array( 'bcb_leiter', $roles ) ) {
			$leiter = array( wp_get_current_user() );
		} else {
			$leiter = get_users( array( 'role' => 'bcb_leiter' ) );
		}

		return array(
			'leiter'   => $leiter,
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
		if ( array_key_exists( self::DATE_FROM_IDENTIFIER, $values ) ) {
			$date_from = \DateTime::createFromFormat( "d.m.Y", $values[ self::DATE_FROM_IDENTIFIER ] );
			if ( $date_from === false ) {
				$errors[] = '"Datum von" ist ungültig';
			}
		}

        if ( array_key_exists( self::LEADER, $values ) ) {
            if ( empty($values[ self::LEADER ]) ) {
                $errors[] = 'Kein Leiter wurde ausgewählt';
            }
        }

        if ($posttype != "draft") {
            if (array_key_exists(self::DATE_TO_IDENTIFIER, $values) && !empty($values[self::DATE_TO_IDENTIFIER])) {
                $date_to = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_TO_IDENTIFIER]);
                if ($date_to === false) {
                    $errors[] = '"Datum bis" ist ungültig';
                } else {
                    if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values)) {
                        $date_from = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_FROM_IDENTIFIER]);
                        if ($date_to < $date_from) {
                            $errors[] = '"Datum bis" muss nach "Datum von" sein.';
                        } elseif ($date_to > $date_from) {
                            if (!array_key_exists(self::SLEEPOVER, $values) || empty($values[self::SLEEPOVER])) {
                                $errors[] = 'Mehrtägige Touren müssen eine Übernachtung beinhalten';
                            }
                        } elseif ($date_to = $date_from) {
                            if (!array_key_exists(self::SLEEPOVER, $values) || !empty($values[self::SLEEPOVER])) {
                                $errors[] = 'Eintägige Touren dürfen keine Übernachtung beinhalten';
                            }
                        }
                    }
                }
            }

            //Test SIGNUP_UNTIL valid
            if (array_key_exists(self::SIGNUP_UNTIL, $values) && !empty($values[self::SIGNUP_UNTIL])) {
                $date_signup = \DateTime::createFromFormat("d.m.Y", $values[self::SIGNUP_UNTIL]);
                if ($date_signup === false) {
                    $errors[] = '"Anmelden bis" ist kein gültiges Datum';
                } else {
                    if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values)) {
                        $date_from = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_FROM_IDENTIFIER]);
                        if ($date_from < $date_signup) {
                            $errors[] = 'Die Anmeldefrist muss vor dem Start der Tour beendet sein.';
                        }
                    }
                }
            }

            if (array_key_exists(self::SIGNUP_UNTIL, $values) && empty($values[self::SIGNUP_UNTIL])) {
                $errors[] = '"Anmelden bis" muss angegeben werden';
            }

            if (array_key_exists(self::SIGNUP_TO, $values) && empty($values[self::SIGNUP_TO])) {
                $errors[] = '"Anmelden an" muss angegeben werden';
            }
        }


		foreach ( $errors as $errorMsg ) {
			FlashMessage::add( FlashMessage::TYPE_ERROR, $errorMsg );
		}

		return count( $errors ) == 0;
	}

}