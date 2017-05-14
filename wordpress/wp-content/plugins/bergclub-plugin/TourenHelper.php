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
    public static function getIsYouth($postId){
        $isYouth = ['BCB', 'Jugend', 'Beides'];
        return $isYouth[self::getMeta($postId, 'isYouth')];
    }

    public static function getDateFrom($postId){
        return self::getDate(self::getMeta($postId, 'dateFrom'));
    }

    public static function getDateTo($postId){
        $dateFrom = self::getDate(self::getMeta($postId, 'dateFrom'));
        $dateTo = self::getDate(self::getMeta($postId, 'dateTo'));
        return $dateFrom != $dateTo ? $dateTo : null;
    }

    public static function getDateDisplayShort($postId){
        if(self::getIsSeveralDays($postId)){
            return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.') . ' - ' . self::getDate(self::getMeta($postId, 'dateTo'), 'd.m.');
        }
        return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.');
    }

    public static function getDateDisplayFull($postId){
        if(self::getIsSeveralDays($postId)){
            $dateFrom = strtotime(self::getMeta($postId, 'dateFrom'));
            $dateTo = strtotime(self::getMeta($postId, 'dateTo'));
            if(date('Y', $dateFrom) != date('Y', $dateTo)){
                return date('d.m.Y', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
            }elseif(date('m', $dateFrom) != date('m', $dateTo)){
                return date('d.m.', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
            }

            return date('d.', $dateFrom) . ' - ' . date('d.m.Y', $dateTo);
        }

        return self::getDate(self::getMeta($postId, 'dateFrom'), 'd.m.Y');
    }

    public static function getIsSeveralDays($postId){
        $dateFrom = self::getDateFrom($postId);
        $dateTo = self::getDateTo($postId);
        return !empty($dateTo) && $dateTo != $dateFrom;
    }
    public static function getLeader($postId){
        return self::getUser(self::getMeta($postId, 'leader'));
    }

    public static function getCoLeader($postId){
        return self::getUser(self::getMeta($postId, 'coLeader'));
    }

    public static function getLeaderAndCoLeader($postId){
        $leaderId = self::getMeta($postId, "leader");
        $leaderName = self::getFullName($leaderId);
        $coLeaderId = self::getMeta($postId, "coLeader");
        if(!empty($coLeaderId)){
            $coLeaderFullName = self::getFullName($coLeaderId);
            $leaderName .= ", " . $coLeaderFullName . " (Co-Leiter)";
        }
        return $leaderName;
    }

    public static function getFullName($userId){
        $firstName = get_user_meta($userId, "first_name", true);
        $lastName = get_user_meta($userId, "last_name", true);
        $fullName = $firstName . " " . $lastName;
        return $fullName;
    }

    public static function getSignupUntil($postId){
        return self::getDate(self::getMeta($postId, 'signupUntil'));
    }


    public static function getSignupTo($postId){
        return self::getUser(self::getMeta($postId, 'signupTo'), true);
    }

    public static function getMeetpoint($postId){
        $id = self::getMeta($postId, 'meetpoint');
        if($id == 1){
            return "Bern HB, Treffpunkt";
        }elseif($id == 2){
            return "Bern HB, auf dem Abfahrtsperron";
        }elseif($id == 3){
            return "Bern HB, auf der Welle";
        }

        $meetpoint = trim(self::getMeta($postId, 'meetpointDifferent'));
        if(!empty($meetpoint)){
            return $meetpoint;
        }

        return null;
    }

    public static function getMeetpointWithTime($postId){
        $meetpoint = self::getMeetpoint($postId);
        $time = self::getMeta($postId, "meetingPointTime");
        if(!empty($meetpoint) && !empty($time)){
            return $meetpoint . ", " . $time . " Uhr";
        }
        return null;
    }

    public static function getType($postId){
        $slug =  self::getMeta($postId, 'type');
        $tourenarten = Option::get('tourenarten');
        if(isset($tourenarten[$slug])){
            return $tourenarten[$slug];
        }

        return null;
    }

    public static function getRequirementsConditional($postId){
        $id =  self::getMeta($postId, 'requirementsConditional');
        if($id == 1){
            return "Leicht";
        }elseif($id == 2){
            return "Mittel";
        }elseif($id == 3){
            return "Schwer";
        }

        return null;
    }

    public static function getTypeWithTechnicalRequirements($postId){
        $type = self::getType($postId);
        $reqTechnical = self::getMeta($postId, 'requirementsTechnical');
        if(!empty($reqTechnical)){
            return $type . ", " . $reqTechnical;
        }
        return null;
    }

    public static function getRiseUpAndDown($postId){
        $riseUp = self::getMeta($postId, "riseUpMeters");
        $riseDown = self::getMeta($postId, "riseDownMeters");
        if(empty($riseUp) && empty($riseDown)){
            return null;
        } else {
            return "<div class=\"icon icon-up\" title=\"Hinauf\"></div>" . $riseUp . " <div class=\"icon icon-down\" title=\"Hinab\"></div>" . $riseDown;
        }
    }

    public static function getDuration($postId){
        $duration = get_post_meta($postId, "_duration", true);
        if(!empty($duration)){
            return $duration;
        }
        return null;
    }

    public static function getAdditionalInfo($postId){
        return nl2br(self::getMeta($postId, 'additionalInfo'));
    }

    public static function getTraining($postId){
        return self::getYesNo(self::getMeta($postId, 'training'));
    }

    public static function getJsEvent($postId){
        return self::getYesNo(self::getMeta($postId, 'jsEvent'));
    }

    public static function getProgram($postId){
        return nl2br(self::getMeta($postId, 'program'));
    }

    public static function getEquipment($postId){
        return nl2br(self::getMeta($postId, 'equipment'));
    }

    public static function __callStatic($method, $args){
        $metaKey = substr($method, 3);
        $metaKey = strtolower(substr($metaKey, 0, 1)) . substr($metaKey, 1);
        return self::getMeta($args[0], $metaKey);
    }

    private static function getYesNo($value){
        return $value ? "Ja" : "Nein";
    }

    private static function getUser($id, $contact = false){
        $user = User::find($id);
        if($user){
            $result = [$user->last_name . ' ' . $user->first_name];
            if($contact){
                if($user->email){
                    $result[] = bcb_email($user->email);
                }
                if($user->phone_private){
                    $result[] = $user->phone_private . " (P)";
                }
                if($user->phone_work){
                    $result[] = $user->phone_work . " (G)";
                }
                if($user->phone_mobile){
                    $result[] = $user->phone_mobile . " (M)";
                }
            }
            return join(', ', $result);
        }

        return null;
    }

    private static function getDate($date, $format = "d.m.Y"){
        $date = strtotime($date);
        if($date > 0){
            return date($format, $date);
        }

        return null;
    }

    private static function getMeta($postId, $key){
        return trim(get_post_meta($postId, '_' . $key, true));
    }
}