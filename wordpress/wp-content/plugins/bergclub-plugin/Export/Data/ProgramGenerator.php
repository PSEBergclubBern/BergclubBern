<?php

namespace BergclubPlugin\Export\Data;

/**
 * Generates the data needed for "Pfarrblatt"
 *
 * @package BergclubPlugin\Export\Data
 */
class ProgramGenerator extends AbstractGenerator
{

    /**
     * Generates and returns an array which holds "Touren" information for the program.
     *
     * The array consists of two main subarrays "data" and "anmeldeTermine".
     * "data" will contain arrays (rows) with entries for the "Touren".
     * <p>
     * Example:
     * <code>
     * ['data' => [
     *      'dateDisplayShort' => '01.07 - 02.07',
     *      'dateDisplayWeekday' => 'Sa - So',
     *      'title' => 'Mehrseillängen-Klettertour Ärmighorn mit Biwak',
     *      'type' => 'Klettertour,
     *      'requirementsTechnical' => null,
     *      'requirementsConditional' => 'Mittel',
     *      'riseUpMeters' => '600 m',
     *      'riseDownMeters' => '1000 m',
     *      'duration' => null,
     *      'meetpoint' => 'Welle',
     *      'meetingPointTime' => '14:30',
     *      'returnBack' => 'So Abend ca. 20 Uhr',
     *      'costs' => null,
     *      'costsFor' => null,
     *      'signupUntil' => null,
     *      'signUpTo' => null,
     *      'additionalInfo' => 'Billet selber lösen: Bern - Kandergrund [...]',
     *      'equipment' => 'Zum Klettern: Bergschuhe für Zu- und Abstieg: [...]',
     *      'food' => 'alles aus dem Rucksack: Zmorge und Picknick nimmt [...]',
     *      'mapMaterial' => null,
     *   ],
     * [...]
     * ];
     * </code>
     * <p>
     * Note:
     * - Every row always have the same amount of entries with the same keys. The value of an entry can be null.
     *
     *
     * "anmeldeTermine" will contain information about the subscription deadline for the "Touren" in data.
     * The key is the deadline date (`Y-m-d`) every key points to an array with at least one subarray.
     * The subarrays have to entries: "signupUntil" (contains the deadline date (`d.m.Y`) and "info" which holds the
     * title and the date of the "Tour".
     * <p>
     * Note:
     * - Every (subarray) always have the same amount of entries with the same keys. The value is always not empty.
     *
     * Arguments needed in constructor: touren-from, touren-to (`Y-m-d`) => The range within "Touren" should be included (attribute
     * fromDate is used).
     *
     * @see AbstractGenerator
     *
     * @return array a list of "Touren" information as described in the class comment. If touren-from or touren-to
     * attribute was not specified trough the constructor, the following array will be returned:
     * `['data' => [], 'anmeldeTermine' => []]`.
     */
    public function getData()
    {
        $data = [];
        if (!empty($this->args['touren-from']) && !empty($this->args['touren-to'])) {
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
                        'value' => date('Y-m-d', strtotime($this->args['touren-from'])),
                        'type' => 'DATE',
                        'compare' => '>='
                    ],
                    [
                        'key' => '_dateFromDB',
                        'value' => date('Y-m-d', strtotime($this->args['touren-to'])),
                        'type' => 'DATE',
                        'compare' => '<='
                    ],
                ],
            ]);

            foreach ($posts as $post) {
                $dateFrom = bcb_touren_meta($post->ID, 'dateFrom');
                $dateTo = bcb_touren_meta($post->ID, 'dateTo');

                $dateDisplayWeekday = $this->getDayOfWeek($dateFrom);
                if (!empty($dateTo) && $dateTo != $dateFrom) {
                    $dateDisplayWeekday .= " - " . $this->getDayOfWeek($dateTo);
                }

                $data[] = [
                    'dateDisplayShort' => $this->oneLine(bcb_touren_meta($post->ID, "dateDisplayShort")),
                    'dateDisplayWeekday' => $this->oneLine($dateDisplayWeekday),
                    'title' => $this->oneLine(get_the_title($post)),
                    'type' => $this->oneLine(bcb_touren_meta($post->ID, "type")),
                    'requirementsTechnical' => $this->oneLine(bcb_touren_meta($post->ID, "requirementsTechnical")),
                    'requirementsConditional' => $this->oneLine(bcb_touren_meta($post->ID, "requirementsConditional")),
                    'riseUpMeters' => $this->oneLine(bcb_touren_meta($post->ID, 'riseUpMeters')),
                    'riseDownMeters' => $this->oneLine(bcb_touren_meta($post->ID, 'riseDownMeters')),
                    'duration' => $this->oneLine(bcb_touren_meta($post->ID, 'duration')),
                    'meetpoint' => $this->oneLine(bcb_touren_meta($post->ID, 'meetpoint')),
                    'meetingPointTime' => $this->oneLine(bcb_touren_meta($post->ID, 'meetingPointTime')),
                    'returnBack' => $this->oneLine(bcb_touren_meta($post->ID, 'returnBack')),
                    'costs' => $this->oneLine(bcb_touren_meta($post->ID, 'costs')),
                    'costsFor' => $this->oneLine(bcb_touren_meta($post->ID, 'costsFor')),
                    'signupUntil' => $this->oneLine(bcb_touren_meta($post->ID, 'signupUntil')),
                    'signUpTo' => $this->oneLine(bcb_touren_meta($post->ID, 'signupToNoLinks')),
                    'additionalInfo' => $this->multiLine(bcb_touren_meta($post->ID, 'additionalInfo')),
                    'equipment' => $this->multiLine(bcb_touren_meta($post->ID, 'equipment')),
                    'food' => $this->oneLine(bcb_touren_meta($post->ID, 'food')),
                    'mapMaterial' => $this->oneLine(bcb_touren_meta($post->ID, 'mapMaterial')),
                ];
            }

            $anmeldeTermine = [];
            foreach ($data as $entry) {
                if (!empty($entry['signupUntil'])) {
                    $items = [];
                    if($entry['type']){
                        $items[] = $entry['type'];
                    }
                    $items[] = $entry['dateDisplayShort'];
                    $anmeldeTermine[date('Y-m-d', strtotime($entry['signupUntil']))][] = [
                        'signupUntil' => date('d.m.', strtotime($entry['signupUntil'])),
                        'info' => $entry['title'] . ' (' . join(', ', $items) . ')',
                    ];
                }
            }
            ksort($anmeldeTermine);

            foreach ($data as &$arr) {
                foreach ($arr as &$item) {
                    if (is_array($item)) {
                        foreach ($item as &$item2) {
                            $item2 = html_entity_decode(strip_tags($item2));
                        }
                    } else {
                        $item = html_entity_decode(strip_tags($item));
                    }
                }
            }

            foreach ($anmeldeTermine as &$arr) {
                foreach ($arr as &$item) {
                    foreach($item as &$item2) {
                        $item2 = html_entity_decode(strip_tags($item2));
                    }
                }
            }
            return ['data' => $data, 'anmeldeTermine' => $anmeldeTermine];
        }

        return ['data' => [], 'anmeldeTermine' => []];
    }

    private function getDayOfWeek($date)
    {
        $days = [
            'So',
            'Mo',
            'Di',
            'Mi',
            'Do',
            'Fr',
            'Sa',
        ];
        return $days[date("w", strtotime($date))];
    }

    private function oneLine($string)
    {
        return trim(str_replace("\n", "", str_replace("<br />", " ", $this->removeCarriageReturn($string))));
    }

    private function removeCarriageReturn($string)
    {
        return str_replace("\r", "", $string);
    }

    private function multiLine($string)
    {
        $lines = explode("<br />", str_replace("\n", "", $this->removeCarriageReturn($string)));
        if (count($lines) == 1 && empty($lines[0])) {
            return [];
        }
        return $lines;
    }

    private function getMonthYear($date)
    {
        $months = [
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

        return $months[date("n", strtotime($date)) - 1] . " " . date("Y", strtotime($date));
    }

    private function createOutput()
    {
        header('Cache-Control: max-age=0');

        $this->word->save('Pfarrblatt ' . date("Y-m-d_H-i-s") . '.docx', 'Word2007', true);
    }

}