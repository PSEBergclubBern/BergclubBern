<?php

global $mockedPostData;

function get_posts(array $args = []){
    global $mockedPostData;

    $result = [];

    foreach($mockedPostData as $key => $arr){
        $result[] = new \BergclubPlugin\Tests\Mocks\WP_PostMock($key, $arr['post_status'], $arr['post_date'], $arr['post_modified']);
    }

    return $result;
}

function get_the_title($post){
    return bcb_touren_meta($post->ID, 'title');
}

function bcb_touren_meta($id, $key){
    global $mockedPostData;

    if (isset($mockedPostData[$id][$key])){
        return $mockedPostData[$id][$key];
    }

    return null;
}

function get_post_meta($postId, $key, $single){
    global $currentMetaValue;
    if(!is_array($currentMetaValue)) {
        return $currentMetaValue;
    }elseif(isset($currentMetaValue[$key])){
        return $currentMetaValue[$key];
    }

    return null;
}

function wp_enqueue_style($slug, $url){
    global $wpEnqueuedStyles;
    $wpEnqueuedStyles[$slug] =  $url;
}

function wp_enqueue_script($slug, $url){
    global $wpEnqueuedScripts;
    $wpEnqueuedScripts[$slug] = $url;
}

function bcb_email($email)
{
    return "<a class='email' data-id='" . base64_encode($email) . "'></a>";
}