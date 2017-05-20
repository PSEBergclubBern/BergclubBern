<?php
/**
 * Created by PhpStorm.
 * User: kevstuder
 * Date: 21.03.17
 * Time: 16:56
 */

namespace BergclubPlugin\Tourenberichte\MetaBoxes;


use BergclubPlugin\FlashMessage;

class Common extends MetaBox
{
    const TOUREN = '_touren';
    const IS_ADULT_OR_YOUTH = '_isYouth';

    public function getUniqueFieldNames()
    {
        return array(
            self::TOUREN,
            self::IS_ADULT_OR_YOUTH,
        );
    }

    public function getUniqueMetaBoxName()
    {
        return 'commontourenberichte';
    }

    public function getUniqueMetaBoxTitle()
    {
        return 'Auf welche Tour bezieht sich dieser Tourenbericht? ';
    }

    public function isValid($values, $posttype)
    {
        $errors = array();
        if (array_key_exists(self::TOUREN, $values)) {
            $tour = $values[self::TOUREN];
            if ($tour == "") {
                $errors[] = 'Tourenbericht muss sich auf eine publizierte Tour beziehen.';
            }
        }

        foreach ($errors as $errorMsg) {
            FlashMessage::add(FlashMessage::TYPE_ERROR, $errorMsg);
        }

        return count($errors) == 0;
    }

    protected function addAdditionalValuesForView(\WP_Post $post)
    {
        $args = array(
            'post_type' => BCB_CUSTOM_POST_TYPE_TOUREN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_DB,
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => '_dateToDB',
                    'value' => date('Y-m-d'),
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        );
        $posts_array = get_posts($args);

        $args1 = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => BCB_CUSTOM_POST_TYPE_TOURENBERICHTE,
            'post_status' => 'autodraft',
        );
        $posts_array_tourenberichte = get_posts($args1);

        foreach ($posts_array_tourenberichte as $post_tourenberichte) {
            $tourId = get_post_meta($post_tourenberichte->ID, self::TOUREN, true);
            if (empty($tourId)) {
                continue;
            }
            foreach ($posts_array as $key => $post_tour) {
                if ($tourId == $post_tour->ID && $tourId != get_post_meta($post->ID, self::TOUREN, true)) {
                    //remove this entry from array
                    unset($posts_array[$key]);
                }
            }
        }

        $posts = [];
        $tourId = get_post_meta($post->ID, '_touren', true);
        if (!empty($tourId)) {
            $posts[] = get_post($tourId);
        }

        return array(
            'touren' => array_merge($posts, $posts_array),
        );
    }

    protected function preSave($values)
    {
        $values = parent::preSave($values);

        // overwrite is youth
        if (isset($values[self::TOUREN])) {
            $values[self::IS_ADULT_OR_YOUTH] = get_post_meta(
                $values[self::TOUREN],
                \BergclubPlugin\Touren\MetaBoxes\Common::IS_ADULT_OR_YOUTH,
                true
            );
        }

        return $values;
    }

}