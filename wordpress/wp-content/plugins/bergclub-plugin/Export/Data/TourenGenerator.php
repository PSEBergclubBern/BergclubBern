<?php

namespace BergclubPlugin\Export\Data;

/**
 * Generates the data needed for "Touren" export.
 *
 * @package BergclubPlugin\Export\Data
 */
class TourenGenerator extends AbstractGenerator
{

    /**
     * Generates and returns an array which holds "Touren" information.
     *
     * The array consists of subarrays (rows).
     * <p>
     * Example:
     * <code>
     * [
     *   [
     *      'Satus' => 'Veröffentlicht',
     *      'Erstellt am' => '01.01.2017',
     *      'Geändert am' => '01.01.2017',
     *      'Datum von' => '21.01.2017',
     *      'Datum bis' => '22.01.2017',
     *      'Titel' => 'Lawinenkurs für Ski- und Snowbordtouren',
     *      'Leiter' => 'Michlig Rudolf',
     *      'Co-Leiter' => 'Heiniger Bettina',
     *      'Art' => 'Diverses',
     *      'Schwierigkeit' => 'WS (wenig schwierig)',
     *      'Konditionell' => 'Mittel',
     *      'Training' => 'Nein',
     *      'J+S Event' => 'Nein',
     *      'Aufstieg' => null,
     *      'Abstieg' => null,
     *      'Dauer' => null,
     *      'Treffpunkt' => 'Bahnhof Bern um 08.00 Uhr unter der Welle auf dem Abfahrtsperron',
     *      'Zeit' => null,
     *      'Rückkehr' => 'Bern an  17:54 Uhr',
     *      'Kosten' => '130',
     *      'Kosten für' => 'ÖV 65 Fr. und 65 Fr für Essen und alle Getränke.',
     *      'Anmeldung bis' => null,
     *      'Anmeldung an' => null,
     *   ],
     * [...]
     * ];
     * </code>
     * <p>
     * Note:
     * - Every row always have the same amount of entries with the same keys. The value of an entry can be null.
     * <p>
     * Arguments needed in constructor:
     * - status => The post status ('publish', 'future', 'draft', 'pending'), multiple values possible (comma separated)
     * - from, to (`Y-m-d`) => The range within "Touren" should be included (attribute fromDate is used).
     *
     * @see AbstractGenerator
     *
     * @return array a list of "Touren" information as described in the class comment. If status, from or to attribute was
     * not specified trough the constructor (or if empty), an empty array will be returned.
     */
    public function getData()
    {
        if (!empty($this->args['status']) && !empty($this->args['from']) && !empty($this->args['to'])) {
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

            $post_status = [
                'publish' => "Veröffentlicht",
                'future' => "Zukünftig (Publizierungsdatum in Zukunft)",
                'draft' => "Entwurf",
                'pending' => "Veröffentlichung beantragt",
            ];

            foreach ($posts as $post) {
                $status = "Unbekannt";
                if (!empty($post_status[$post->post_status])) {
                    $status = $post_status[$post->post_status];
                }
                $data[] = [
                    'Status' => $status,
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
                    'Zeit' => bcb_touren_meta($post->ID, 'meetingPointTime'),
                    'Rückkehr' => bcb_touren_meta($post->ID, 'returnBack'),
                    'Kosten' => bcb_touren_meta($post->ID, 'costs'),
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