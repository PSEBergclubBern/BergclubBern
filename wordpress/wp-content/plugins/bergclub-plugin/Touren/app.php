<?php

define("BCB_CUSTOM_POST_TYPE_TOUREN", "touren");

$metaBoxes = array(
    new \BergclubPlugin\Touren\MetaBoxes\Common(),
    new \BergclubPlugin\Touren\MetaBoxes\MeetingPoint(),
    new \BergclubPlugin\Touren\MetaBoxes\Tour(),
);

foreach ($metaBoxes as $metaBox) {
    add_action('add_meta_boxes', [$metaBox, 'add']);
    add_action('save_post', [$metaBox, 'save']);
}

add_action('admin_notices', function() { echo \BergclubPlugin\FlashMessage::show(); } );
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    wp_enqueue_style('jquery-ui');
    wp_enqueue_script(
        'bcb-datepicker-script',
        plugins_url('views/script.js', __FILE__),
        array('jquery-ui-datepicker'),
        false,
        true
    );
    wp_enqueue_style(
        'bcb-timepicker-style',
        plugins_url('views/jquery.timepicker.css', __FILE__)
    );
    wp_enqueue_script(
        'bcb-timepicker-script',
        plugins_url('views/jquery.timepicker.min.js', __FILE__)
    );
});


function bcb_create_new_metabox_context( $post ) {
    do_meta_boxes( null, 'bcb-metabox-holder', $post );
}

add_action( 'edit_form_after_title', 'bcb_create_new_metabox_context' );