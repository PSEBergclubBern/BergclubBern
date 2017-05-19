<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 22:23
 */

namespace BergclubPlugin\Export\Data;


use BergclubPlugin\MVC\Models\User;

class TourenGenerator extends AbstractGenerator
{
    public function getData()
    {
        $status = $this->args['status'];
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

        $post_status = [
            'publish' => "Veröffentlicht",
            'future' => "Zukünftig (Publizierungsdatum in Zukunft)",
            'draft' => "Entwurf",
            'pending' => "Veröffentlichung beantragt",
        ];

        foreach($posts as $post){
            $status = "Unbekannt";
            if(!empty($post_status[$post->post_status])){
                $status = $post_status[$post->post_status];
            }
            $data[] = [
                'Satus' => $status,
                'Erstellt am' => date('d.m.Y', strtotime($post->post_date)),
                'Geändert am' => date('d.m.Y', strtotime($post->post_modified)),
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
                'Kosten' => bcb_touren_meta($post->ID, 'costs'),
                'Kosten für' => bcb_touren_meta($post->ID, 'costsFor'),
                'Anmeldung bis' => bcb_touren_meta($post->ID, 'signupUntil'),
                'Anmeldung an' => bcb_touren_meta($post->ID, 'signupTo'),
            ];
        }
        return $data;
    }
}