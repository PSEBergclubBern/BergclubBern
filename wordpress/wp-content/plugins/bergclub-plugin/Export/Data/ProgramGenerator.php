<?php

namespace BergclubPlugin\Export\Data;


class ProgramGenerator extends AbstractGenerator
{
    public function getData()
    {
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
                    'value' => date('Y-m-d', strtotime($_GET['touren-from'])),
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateFromDB',
                    'value' => date('Y-m-d', strtotime($_GET['touren-to'])),
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $dateFrom = bcb_touren_meta($post->ID, 'dateFrom');
            $dateTo = bcb_touren_meta($post->ID, 'dateTo');

            $dateDisplayWeekday = $this->getDayOfWeek($dateFrom);
            if(!empty($dateTo) && $dateTo != $dateFrom){
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
                'costs' => $this->oneLine(bcb_touren_meta($post->ID, 'oosts')),
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
        foreach($data as $entry) {
            if(!empty($entry['signupUntil'])){
                $anmeldeTermine[date('Y-m-d', strtotime($entry['signupUntil']))][] = [
                    'signupUntil' => date('d.m.', strtotime($entry['signupUntil'])),
                    'info' => $entry['title'] . ' (' . $entry['type'] . ', ' . $entry['dateDisplayShort'] . ')',
                ];
            }
        }
        ksort($anmeldeTermine);

        foreach($data as &$arr){
            foreach($arr as &$item) {
                if(is_array($item)) {
                    foreach($item as &$item2){
                        $item2 = html_entity_decode(strip_tags($item2));
                    }
                }else {
                    $item = html_entity_decode(strip_tags($item));
                }
            }
        }

        foreach($anmeldeTermine as &$arr){
            foreach($arr as &$item) {
                $item = html_entity_decode(strip_tags($item));
            }
        }

        return ['data' => $data, 'anmeldeTermine' => $anmeldeTermine];
    }

    private function oneLine($string){
        return trim(str_replace("\n", "", str_replace("<br />", " ", $this->removeCarriageReturn($string))));
    }

    private function multiLine($string){
        $lines = explode("<br />", str_replace("\n", "", $this->removeCarriageReturn($string)));
        if(count($lines) == 1 && empty($lines[0])){
            return [];
        }
        return $lines;
    }

    private function removeCarriageReturn($string){
        return str_replace("\r", "", $string);
    }

    private function getMonthYear($date){
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

        return $months[date("n", strtotime($date))-1]." ".date("Y", strtotime($date));
    }

    private function getDayOfWeek($date){
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

    private function createOutput(){
        header('Cache-Control: max-age=0');

        $this->word->save('Pfarrblatt '.date("Y-m-d_H-i-s").'.docx', 'Word2007', true);
    }

}