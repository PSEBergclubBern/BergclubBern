<?php

namespace BergclubPlugin\Export\Data;


class CalendarGenerator extends AbstractGenerator
{
    public function getData()
    {
        $year = date("Y");
        if(isset($this->args['year'])){
            $year = $this->args['year'];
        }

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
                    'value' => $year . '-01-01',
                    'type' => 'DATE',
                    'compare' => '>='
                ],
                [
                    'key' => '_dateToDB',
                    'value' => $year . '-12-31',
                    'type' => 'DATE',
                    'compare' => '<='
                ],
            ],
        ]);

        foreach($posts as $post){
            $title = get_the_title($post);
            $date_from = bcb_touren_meta($post->ID, "dateFrom");
            $date_to =  bcb_touren_meta($post->ID, "dateTo");
            $type = bcb_touren_meta($post->ID, "type");
            $reqTechnical = bcb_touren_meta($post->ID, "requirementsTechnical");
            $isYouth = bcb_touren_meta($post->ID, "isYouthRaw");

            if(!empty($date_from)) {
                $item = [];

                if($isYouth == 1){
                    $item['isYouth'] = 'Jugend';
                }
                if (!empty($type)) {
                    $item['type'] = $type;
                    if (!empty($reqTechnical)) {
                        $item['req_technical'] = $reqTechnical;
                    }
                }

                if (bcb_touren_meta($post->ID, "isSeveralDays")) {
                    $item['date_to'] = "bis " . date("d.m.", strtotime($date_to));
                }

                $data[date('Y-m-d', strtotime($date_from))][] = $title . " (" . join(', ', $item) .")";
            }
        }

        return $data;
    }
}