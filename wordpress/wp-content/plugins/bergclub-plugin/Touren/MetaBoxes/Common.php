<?php

namespace BergclubPlugin\Touren\MetaBoxes;

use BergclubPlugin\FlashMessage;
use BergclubPlugin\MVC\Models\User;

/**
 * Class Common
 *
 * This class represents the general metabox for tours
 *
 * @package BergclubPlugin\Touren\MetaBoxes
 */
class Common extends MetaBox
{
    const DATE_FROM_IDENTIFIER = '_dateFrom';
    const DATE_TO_IDENTIFIER = '_dateTo';
    const DATE_FROM_DB = '_dateFromDB';
    const DATE_TO_DB = '_dateToDB';
    const IS_ADULT_OR_YOUTH = '_isYouth';
    const LEADER = '_leader';
    const CO_LEADER = '_coLeader';
    const SIGNUP_UNTIL = '_signupUntil';
    const SIGNUP_TO = '_signupTo';
    const SLEEPOVER = '_sleepOver';

    /**
     * @return array
     */
    public function getUniqueFieldNames()
    {
        return array(
            self::DATE_FROM_IDENTIFIER,
            self::DATE_TO_IDENTIFIER,
            self::DATE_FROM_DB,
            self::DATE_TO_DB,
            self::IS_ADULT_OR_YOUTH,
            self::LEADER,
            self::CO_LEADER,
            self::SIGNUP_UNTIL,
            self::SIGNUP_TO,
            self::SLEEPOVER,
        );
    }

    /**
     * @return string
     */
    public function getUniqueMetaBoxName()
    {
        return 'common';
    }

    /**
     * @return string
     */
    public function getUniqueMetaBoxTitle()
    {
        return 'Zusatzinformationen';
    }

    /**
     * @param $values   array   Values for this class
     * @param $posttype String  Posttype (draft, publish, ...)
     * @return bool
     */
    public function isValid($values, $posttype)
    {
        $errors = array();
        if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values)) {
            $date_from = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_FROM_IDENTIFIER]);
            if ($date_from === false) {
                $errors[] = '"Datum von" ist ungültig';
            }
        }

        if (array_key_exists(self::LEADER, $values)) {
            if (empty($values[self::LEADER])) {
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
                                $errors[] = 'Bei mehrtägigen Touren muss das Feld "Übernachtung" ausgefüllt werden.';
                            }
                        } else {
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


        foreach ($errors as $errorMsg) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
        }

        return count($errors) == 0;
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
        if ((!array_key_exists(self::DATE_TO_IDENTIFIER, $values)
                || empty($values[self::DATE_TO_IDENTIFIER]))
            && array_key_exists(self::DATE_FROM_IDENTIFIER, $values)
        ) {
            $values[self::DATE_TO_IDENTIFIER] = $values[self::DATE_FROM_IDENTIFIER];
        }

        if (array_key_exists(self::DATE_FROM_IDENTIFIER, $values) && !empty($values[self::DATE_FROM_IDENTIFIER])) {
            $date_from = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_FROM_IDENTIFIER]);
            if ($date_from !== false) {
                $values[self::DATE_FROM_DB] = $date_from->format('Y-m-d');
            }
        }

        if (array_key_exists(self::DATE_TO_IDENTIFIER, $values) && !empty($values[self::DATE_TO_IDENTIFIER])) {
            $date_to = \DateTime::createFromFormat("d.m.Y", $values[self::DATE_TO_IDENTIFIER]);
            if ($date_to !== false) {
                $values[self::DATE_TO_DB] = $date_to->format('Y-m-d');
            }
        }

        // hook for changes in field IS_ADULT_OR_YOUTH
        $queryArguments = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => BCB_CUSTOM_POST_TYPE_TOURENBERICHTE,
            'post_status' => 'autodraft',
        );
        $tourenberichte = get_posts($queryArguments);
        foreach ($tourenberichte as $tourenbericht) {
            /** @var $tourenbericht \WP_Post */
            $tourId = get_post_meta(
                $tourenbericht->ID,
                \BergclubPlugin\Tourenberichte\MetaBoxes\Common::TOUREN,
                true
            );
            if ($tourId == $values['post_ID']) {
                update_post_meta(
                    $tourenbericht->ID,
                    \BergclubPlugin\Tourenberichte\MetaBoxes\Common::IS_ADULT_OR_YOUTH,
                    $values[self::IS_ADULT_OR_YOUTH]
                );
            }

        }

        return $values;
    }

    /**
     * @return array
     */
    protected function addAdditionalValuesForView()
    {
        global $post;

        $leiter = [];

        $coLeiter = [];

        $object = new \stdClass();
        $object->ID = 0;
        $object->first_name = '';
        $object->last_name = '';

        $coLeiter[] = $object;

        $events = [];

        if ($post) {
            $leader = get_post_meta($post->ID, '_leader', true);
            if (!empty($leader)) {
                $leader = User::find($leader);
                if (!empty($leader)) {
                    $leiter[] = $leader;
                }
            }

            $coLeader = get_post_meta($post->ID, '_coLeader', true);
            if (!empty($coLeader)) {
                $coLeader = User::find($coLeader);
                if (!empty($coLeader)) {
                    $coLeiter[] = $coLeader;
                }
            }
        }

        $roles = wp_get_current_user()->roles;
        if ((in_array('bcb_leiter', $roles) || in_array('bcb_leiter_jugend', $roles)) && !in_array('bcb_tourenchef', $roles) && !in_array('bcb_tourenchef_jugend', $roles) && !in_array('bcb_redaktion', $roles)) {
            $leiter = array_merge($leiter, array(User::findCurrent()));
        } else {
            $leiter = array_merge($leiter, User::findByRole('bcb_leiter'));
        }


        return array(
            'leiter' => $leiter,
            'coLeiter' => array_merge($coLeiter, User::findMitglieder()),
            'signUpTo' => array_merge(get_users(array('role' => 'bcb_leiter')), User::findMitglieder()),
            'events' => array(0 => 'BCB', 1 => 'BCB Jugend', 2 => 'Beides'),
        );
    }
}