<?php

namespace BergclubPlugin;

use BergclubPlugin\MVC\Models\Option;
use BergclubPlugin\MVC\Models\User;

/**
 * Helps to convert the touren post meta data as needed in theme and export.
 *
 * Usage example:
 * ```
 * while ($query->have_posts()) : $query->the_post();
 *      $dateFrom = bcb_touren_meta(get_the_ID(), 'dateFrom');
 *      [...]
 * }
 * ```
 */
class TourenHelper
{

    /**
     * Returns 'BCB', 'Jugend' or 'Beides' based on the 'isYouth' meta value (0, 1 or 2).
     *
     * @param $postId
     * @return string
     */
    public static function getIsYouth($postId)
    {
        $isYouth = ['BCB', 'Jugend', 'Beides'];
        return $isYouth[self::getMeta($postId, 'isYouth')];
    }

    private static function getMeta($postId, $key)
    {
        return trim(get_post_meta($postId, '_' . $key, true));
    }

    /**
     * Returns the raw value of the 'isYouth' meta value (0, 1, 2).
     *
     * @param $postId
     * @return int
     */
    public static function getIsYouthRaw($postId)
    {
        return self::getMeta($postId, 'isYouth');
    }

    /**
     * Returns a short display date.
     * If the tour is over several days it will return `d.m. - d.m.` (dateFrom and dateTo meta value).
     * Otherwise it will return `d.m.` (dateFrom meta value).
     *
     * @param $postId
     * @return string
     */
    public static function getDateDisplayShort($postId)
    {
        if (self::getIsSeveralDays($postId)) {
            return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.') . ' - ' . self::getDate(self::getMeta($postId, 'dateTo'), 'd.m.');
        }
        return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.');
    }

    /**
     * Checks if the tour is over several days or not.
     *
     * @param $postId
     * @return bool true when several days, false otherwise
     */
    public static function getIsSeveralDays($postId)
    {
        $dateFrom = self::getDateFrom($postId);
        $dateTo = self::getDateTo($postId);
        return !empty($dateTo) && $dateTo != $dateFrom;
    }

    /**
     * Returns the 'dateFrom' meta value in the format `d.m.Y`
     *
     * @param $postId
     * @return string
     */
    public static function getDateFrom($postId)
    {
        return self::getDate(self::getMeta($postId, 'dateFrom'));
    }

    private static function getDate($date, $format = "d.m.Y")
    {
        $date = strtotime($date);
        if ($date > 0) {
            return date($format, $date);
        }

        return null;
    }

    /**
     * Returns the 'dateTo' meta value in the format `d.m.Y`
     * @param $postId
     * @return string
     */
    public static function getDateTo($postId)
    {
        $dateFrom = self::getDate(self::getMeta($postId, 'dateFrom'));
        $dateTo = self::getDate(self::getMeta($postId, 'dateTo'));
        return $dateFrom != $dateTo ? $dateTo : null;
    }

    /**
     * Returns a full display date.
     *
     * If the tour is over several days:
     * - `d.m.Y - d.m.Y` if dateFrom and dateTo meta values have not the same year
     * - `d.m. - d.m.Y` if dateFrom and dateTo meta values have not the same month
     * - `d. - d.m.Y` otherwise
     *
     * and `d.m.Y` if the tour is only one day:
     *
     * @param $postId
     * @return string
     */
    public static function getDateDisplayFull($postId)
    {
        if (self::getIsSeveralDays($postId)) {
            $dateFrom = strtotime(self::getMeta($postId, 'dateFrom'));
            $dateTo = strtotime(self::getMeta($postId, 'dateTo'));
            if (date('Y', $dateFrom) != date('Y', $dateTo)) {
                return date('d.m.Y', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
            } elseif (date('m', $dateFrom) != date('m', $dateTo)) {
                return date('d.m.', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
            }

            return date('d.', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
        }

        return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.Y');
    }

    /**
     * Returns the data for the "Leiter"
     * <code>
     * last_name first_name [phone_private (P)] [phone_work (G)] [phone_mobile (M)] [<a href="mailto:email">email</a>
     * </code>
     *
     * @param $postId
     * @return string
     */
    public static function getLeader($postId)
    {
        return self::getUser(self::getMeta($postId, 'leader'));
    }

    private static function getUser($id, $contact = false, $noLinks = false)
    {
        $user = User::find($id);
        if ($user) {
            $result = [$user->last_name . ' ' . $user->first_name];
            if ($contact) {
                if ($user->email) {
                    if (!$noLinks) {
                        $result[] = bcb_email($user->email);
                    } else {
                        $result[] = $user->email;
                    }
                }
                if ($user->phone_private) {
                    $result[] = $user->phone_private . " (P)";
                }
                if ($user->phone_work) {
                    $result[] = $user->phone_work . " (G)";
                }
                if ($user->phone_mobile) {
                    $result[] = $user->phone_mobile . " (M)";
                }
            }
            return join(', ', $result);
        }

        return null;
    }

    /**
     * Returns the data for the "Co-Leiter"
     * <code>
     * last_name first_name [phone_private (P)] [phone_work (G)] [phone_mobile (M)] [<a href="mailto:email">email</a>
     * </code>
     *
     * @param $postId
     * @return string
     */
    public static function getCoLeader($postId)
    {
        return self::getUser(self::getMeta($postId, 'coLeader'));
    }

    /**
     * Returns the data for the "Co-Leiter"
     * <code>
     * leader-first_name leader-last_name[, co-leader-first_name co-leader-last_name]
     * </code>
     *
     * @param $postId
     * @return string
     */
    public static function getLeaderAndCoLeader($postId)
    {
        $leaderId = self::getMeta($postId, "leader");
        $leaderName = self::getFullName($leaderId);
        $coLeaderId = self::getMeta($postId, "coLeader");
        if (!empty($coLeaderId)) {
            $coLeaderFullName = self::getFullName($coLeaderId);
            $leaderName .= ", " . $coLeaderFullName . " (Co-Leiter)";
        }
        return $leaderName;
    }

    /**
     * Returns the full name (`first_name last_name`) for the given user id
     *
     * @param $userId
     * @return string
     */
    public static function getFullName($userId)
    {
        $firstName = get_user_meta($userId, "first_name", true);
        $lastName = get_user_meta($userId, "last_name", true);
        $fullName = $firstName . " " . $lastName;
        return $fullName;
    }

    /**
     * Returns the 'signupUntil' meta value in the format `d.m.Y`
     *
     * @param $postId
     * @return string
     */
    public static function getSignupUntil($postId)
    {
        return self::getDate(self::getMeta($postId, 'signupUntil'));
    }

    /**
     * Returns the data for the "Anmelden an" field
     * <code>
     * last_name first_name [phone_private (P)] [phone_work (G)] [phone_mobile (M)] [<a href="mailto:email">email</a>
     * </code>
     *
     * @param $postId
     * @return string
     */
    public static function getSignupTo($postId)
    {
        return self::getUser(self::getMeta($postId, 'signupTo'), true);
    }

    /**
     * Returns the data for the "Anmelden an" field without <a> tag
     *
     * @see TourenHelper::getSignupTo()
     *
     * @param $postId
     * @return string
     */
    public static function getSignupToNoLinks($postId)
    {
        return self::getUser(self::getMeta($postId, 'signupTo'), true, true);
    }

    /**
     * Returns the "Treffpunkt" information together with the meta field 'meetingPointTime' value.
     * <code>
     * meetpoint[, meetingPointTime Uhr]
     * </code>
     *
     * @see TourenHelper::getMeetpoint()
     *
     * @param $postId
     * @return string
     */
    public static function getMeetpointWithTime($postId)
    {
        $meetpoint = self::getMeetpoint($postId);
        $time = self::getMeta($postId, "meetingPointTime");
        if (!empty($meetpoint) && !empty($time)) {
            return $meetpoint . ", " . $time . " Uhr";
        }
        return null;
    }

    /**
     * Returns the data for the field "Treffpunkt" depending of the value of the meta field 'meetpoint'
     *
     * - 0: the value of the meta field 'meetpointDifferent'
     * - 1: "Bern HB, Treffpunkt"
     * - 2: "Bern HB, auf dem Abfahrtsperron"
     * - 3: "Bern HB, auf der Welle
     *
     * @param $postId
     * @return string
     */
    public static function getMeetpoint($postId)
    {
        $id = self::getMeta($postId, 'meetpoint');
        if ($id == 1) {
            return "Bern HB, Treffpunkt";
        } elseif ($id == 2) {
            return "Bern HB, auf dem Abfahrtsperron";
        } elseif ($id == 3) {
            return "Bern HB, auf der Welle";
        }

        $meetpoint = trim(self::getMeta($postId, 'meetpointDifferent'));
        if (!empty($meetpoint)) {
            return $meetpoint;
        }

        return null;
    }

    /**
     * Returns the data for the "Anforderungen konditionell" based on the value of the meta field 'requirementsConditional':
     *
     * - 0: null
     * - 1: 'Leicht'
     * - 2: 'Mittel'
     * - 3: 'Schwer'
     *
     * @param $postId
     * @return null|string
     */
    public static function getRequirementsConditional($postId)
    {
        $id = self::getMeta($postId, 'requirementsConditional');
        if ($id == 1) {
            return "Leicht";
        } elseif ($id == 2) {
            return "Mittel";
        } elseif ($id == 3) {
            return "Schwer";
        }

        return null;
    }

    /**
     * Returns the type of the tour together with the technical requirements.
     * <code>
     * type[, requirementsTechnical]
     * </code>
     *
     * @param $postId
     * @return null|string
     */
    public static function getTypeWithTechnicalRequirements($postId)
    {
        $type = self::getType($postId);
        $reqTechnical = self::getMeta($postId, 'requirementsTechnical');
        if (!empty($reqTechnical)) {
            return $type . ", " . $reqTechnical;
        }
        return null;
    }

    /**
     * Returns the type of the tour.
     * See the module "Stammdaten" for more information.
     *
     * @param $postId
     * @return null|string
     */
    public static function getType($postId)
    {
        $slug = self::getMeta($postId, 'type');
        $tourenarten = Option::get('tourenarten');
        if (isset($tourenarten[$slug])) {
            return $tourenarten[$slug];
        }

        return null;
    }

    /**
     * Returns the data for the fields "Aufstieg" und "Abstieg".
     * <code>
     * <div class="icon icon-up" title="Aufstieg"></div> riseUp <div class="icon icon-down" title="Abstieg"></div> riseDown
     * </code>
     *
     * @param $postId
     * @return null|string
     */
    public static function getRiseUpAndDown($postId)
    {
        $riseUp = self::getMeta($postId, "riseUpMeters");
        $riseDown = self::getMeta($postId, "riseDownMeters");
        if (empty($riseUp) && empty($riseDown)) {
            return null;
        } else {
            return "<div class=\"icon icon-up\" title=\"Aufstieg\"></div>" . $riseUp . " <div class=\"icon icon-down\" title=\"Abstieg\"></div>" . $riseDown;
        }
    }

    /**
     * Returns the data for "Gesamtdauer"
     *
     * @param $postId
     * @return mixed|null
     */
    public static function getDuration($postId)
    {
        $duration = get_post_meta($postId, "_duration", true);
        if (!empty($duration)) {
            return $duration;
        }
        return null;
    }

    /**
     * Returns the data for "Besonderes", '\n' replaced with '<br>'
     *
     * @param $postId
     * @return string
     */
    public static function getAdditionalInfo($postId)
    {
        return nl2br(self::getMeta($postId, 'additionalInfo'));
    }

    /**
     * Returns 'Ja' or 'Nein' based on the value of the meta field 'training'.
     *
     * @param $postId
     * @return string
     */
    public static function getTraining($postId)
    {
        return self::getYesNo(self::getMeta($postId, 'training'));
    }

    private static function getYesNo($value)
    {
        return $value ? "Ja" : "Nein";
    }

    /**
     * Returns 'Ja' or 'Nein' based on the value of the meta field 'jsEvent'.
     *
     * @param $postId
     * @return string
     */
    public static function getJsEvent($postId)
    {
        return self::getYesNo(self::getMeta($postId, 'jsEvent'));
    }

    /**
     * Returns the data for "Programm", '\n' replaced with '<br>'
     *
     * @param $postId
     * @return string
     */
    public static function getProgram($postId)
    {
        return nl2br(self::getMeta($postId, 'program'));
    }

    /**
     * Returns the data for "Ausrüstung", '\n' replaced with '<br>'
     *
     * @param $postId
     * @return string
     */
    public static function getEquipment($postId)
    {
        return nl2br(self::getMeta($postId, 'equipment'));
    }

    public static function __callStatic($method, $args)
    {
        $metaKey = substr($method, 3);
        $metaKey = strtolower(substr($metaKey, 0, 1)) . substr($metaKey, 1);
        return self::getMeta($args[0], $metaKey);
    }
}