<?php

namespace BergclubPlugin\Export\Data;

/**
 * Generates the data needed for "Touren" export.
 *
 * @package BergclubPlugin\Export\Data
 */
class TourenGenerator extends AbstractGenerator
{
    public function getData()
    {
        if(!empty($this->args['status']) && !empty($this->args['from']) && !empty($this->args['to'])) {
            $status = explode(",", $this->args['status']);
            $from = $this->args['from'];
            $to = $this->args['to'];
            $data = [];

            $posts = get_posts([
                'posts_per_page' => -1,
                'post_status' => $status,
                'post_type' => 'touren',
                'order' => 'ASC',
                'orderby' => '_dateFromDB',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => '_dateFromDB',
                        'value' => date('Y-m-d', strtotime($from)),
                        'type' => 'DATE',
                        'compare' => '>='
                    ],
                    [
                        'key' => '_dateFromDB',
                        'value' => date('Y-m-d', strtotime($to)),
                        'type' => 'DATE',
                        'compare' => '<='
                    ],
                ],
            ]);

            foreach ($posts as $post) {
                $data[] = [
                    'Datum von' => bcb_touren_meta($post->ID, "dateFrom"),
                    'Datum bis' => bcb_touren_meta($post->ID, "dateTo"),
                    'Titel' => get_the_title($post),
                    'Leiter' => bcb_touren_meta($post->ID, 'leader'),
                    'Co-Leiter' => bcb_touren_meta($post->ID, 'coLeader'),
                    'Art' => bcb_touren_meta($post->ID, "type"),
                    'Schwierigkeit' => bcb_touren_meta($post->ID, "requirementsTechnical"),
                    'Konditionell' => bcb_touren_meta($post->ID, "requirementsConditional"),
                    'Training' => bcb_touren_meta($post->ID, 'training'),
                    'J+S Event' => bcb_touren_meta($post->ID, 'jsEvent'),
                    'Aufstieg' => bcb_touren_meta($post->ID, 'riseUpMeters'),
                    'Abstieg' => bcb_touren_meta($post->ID, 'riseDownMeters'),
                    'Dauer' => bcb_touren_meta($post->ID, 'duration'),
                    'Treffpunkt' => bcb_touren_meta($post->ID, 'meetpoint'),
                    'Zeit' => bcb_touren_meta($post->ID, 'meetingpointTime'),
                    'Rückkehr' => bcb_touren_meta($post->ID, 'returnBack'),
                    'Kosten' => bcb_touren_meta($post->ID, 'oosts'),
                    'Kosten für' => bcb_touren_meta($post->ID, 'costsFor'),
                    'Anmeldung bis' => bcb_touren_meta($post->ID, 'signupUntil'),
                    'Anmeldung an' => bcb_touren_meta($post->ID, 'signupTo'),
                ];
            }

            return $data;

        }

        return [];
    }
}