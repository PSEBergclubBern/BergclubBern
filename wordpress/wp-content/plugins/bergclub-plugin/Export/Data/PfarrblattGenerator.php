<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 14.05.2017
 * Time: 22:32
 */

namespace BergclubPlugin\Export\Data;

/**
 * Generates the data needed for "Pfarrblatt"
 *
 * @package BergclubPlugin\Export\Data
 */
class PfarrblattGenerator extends AbstractGenerator
{

    /**
     * Generates and returns an array which holds rows with "Touren" information as strings.
     *
     * Arguments needed in constructor: from, to (`Y-m-d`) => The range within "Touren" should be included (attribute
     * fromDate is used).
     *
     * @see AbstractGenerator
     *
     * <p>
     * Example:
     * <code>
     * [
     *   'Mittwoch, 4. Januar: Diverses, Neujahrshöck.',
     *   'Samstag, 7. Januar: Wanderung, Schallberg-Rosswald-Fleschboden, Anmeldung an: Fritz Muster, 031 234 56 78 (P)',
     *   [...]
     * ],
     * </code>
     * <p>
     * Note:
     * - Every row always have the same amount of entries with the same keys. The value of an entry can be null.
     * - The entry with the key 'Funktionen' contains a list with comma separated functionary roles of the member (if
     *   user has functionary roles, null otherwise)
     *
     * @return array a list of "Touren" as described in the class comment. If from or to attribute was not specified trough
     * the constructor, an empty array will be returned.
     */
    public function getData()
    {
        if(!empty($this->args['from']) && !empty($this->args['to'])) {
            $from = $this->args['from'];
            $to = $this->args['to'];
            $data = [];

            $posts = get_posts([
                'posts_per_page' => -1,
                'post_status' => 'publish',
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
                $item = $this->getPfarrblattDate(bcb_touren_meta($post->ID, "dateFrom"), bcb_touren_meta($post->ID, "dateTo")) . ": ";
                $item .= bcb_touren_meta($post->ID, "type") . ", ";
                $item .= get_the_title($post);
                $signupTo = bcb_touren_meta($post->ID, "signUpToNoLinks");
                if (!empty($signupTo)) {
                    $item .= ", Anmeldung an: " . $signupTo;
                }

                $data[] = $item;
            }

            foreach ($data as &$item) {
                $item = html_entity_decode(strip_tags($item));
            }

            return $data;
        }
        return [];
    }

    private function getPfarrblattDate($from, $to)
    {
        $weekday = [
            'Sonntag',
            'Montag',
            'Dienstag',
            'Mittwoch',
            'Donnerstag',
            'Freitag',
            'Samstag',
        ];

        $month = [
            'Januar',
            'Februar',
            'März',
            'April',
            'Mai',
            'Juni',
            'Juli',
            'August',
            'September',
            'Oktober',
            'November',
            'Dezember',
        ];

        $tmFrom = strtotime($from);

        if (empty($to) || $to == $from) {
            return $weekday[date('w', $tmFrom)] . ', ' . date('j', $tmFrom) . '. ' . $month[date('n', $tmFrom) - 1];
        } else {
            $tmTo = strtotime($to);
            $result = $weekday[date('w', $tmFrom)] . '/' . $weekday[date('w', $tmTo)] . ", ";
            $monthFrom = $month[date('n', $tmFrom) - 1];
            $monthTo = $month[date('n', $tmTo) - 1];
            if ($monthFrom == $monthTo) {
                $result .= date('j', $tmFrom) . './' . date('j', $tmTo) . '. ' . $monthFrom;
            } else {
                $result .= date('j', $tmFrom) . '. ' . $monthFrom . '/' . date('j', $tmTo) . '. ' . $monthTo;
            }

            return $result;
        }
    }

}