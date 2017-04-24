<?php

use BergclubPlugin\MVC\Models\Option;

$posts = get_posts([
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_type' => 'touren',
    'order' => 'DESC',
    'orderby' => '_dateToDB',
    'meta_query' => [
        [
            'key' => '_dateToDB',
            'value' => date('Y-m-d'),
            'type' => 'DATE',
            'compare' => '<',
        ],
    ],
]);

$rueckmeldungen;

foreach ( $posts as $post ){

    $rueckmeldungen[] = [
        'id' => $post->ID,
        'leader' => bcb_touren_meta( $post->ID, 'leader' ),
        'title' => get_the_title( $post ),
        'coLeader' => null,
        'externLeader' => null,
        'participants' => null,
        'externParticipants' => null,
        'executed' => false,
        'programDivergence' => null,
        'shortReport' => null,
        'flatCharge' => null,
        'tour' => null,
        'isSeveralDays' => bcb_touren_meta( $post->ID, 'isSeveralDays' ),
        'sleepOver' => null,
        'paymentIsForLeader' => false,
        'numberOfParticipants' => null,
        'hasFeedback' => false,
        'approved' => false,
        'paid' => false,
    ];

}

Option::set('tourenrueckmeldung', $rueckmeldungen);