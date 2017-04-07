<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17.03.2017
 * Time: 10:09
 */

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



function bcb_register_my_tourenverwaltung() {

    /**
     * Post Type: Touren.
     */

    $labels = array(
        "name" => __( 'Touren', '' ),
        "singular_name" => __( 'Tour', '' ),
        "menu_name" => __( 'Touren', '' ),
        "all_items" => __( 'Touren', '' ),
        "add_new" => __( 'Tour erfassen', '' ),
        "add_new_item" => __( 'Neue Tour erfassen', '' ),
        "edit_item" => __( 'Tour anpassen', '' ),
        "new_item" => __( 'Neue Tour', '' ),
        "view_item" => __( 'Tour ansehen', '' ),
        "view_items" => __( 'Touren ansehen', '' ),
        "search_items" => __( 'Tour suchen', '' ),
    );

    $args = array(
        "label" => __( 'Touren', '' ),
        "labels" => $labels,
        "description" => "Touren des Bergclubs Bern",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => ['tour', 'touren'],
        "map_meta_cap" => true,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail", "custom-fields" ),
        "menu_position" => 5, //below Posts according to https://codex.wordpress.org/Function_Reference/register_post_type
    );

    register_post_type( BCB_CUSTOM_POST_TYPE_TOUREN, $args );
}

add_action( 'init', 'bcb_register_my_tourenverwaltung' );


function bcb_create_new_metabox_context( $post ) {
    do_meta_boxes( null, 'bcb-metabox-holder', $post );
}

add_action( 'edit_form_after_title', 'bcb_create_new_metabox_context' );