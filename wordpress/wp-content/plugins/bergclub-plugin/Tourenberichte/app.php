<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17.03.2017
 * Time: 10:09
 */

define("BCB_CUSTOM_POST_TYPE_Tourenberichte", "tourenberichte");

$metaBoxes = array(
    new \BergclubPlugin\Tourenberichte\MetaBoxes\CommonTourenberichte(),
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



function bcb_register_my_tourenberichteverwaltung() {

    /**
     * Post Type: Touren.
     */

    $labels = array(
        "name" => __( 'Tourenberichte', '' ),
        "singular_name" => __( 'Tourenbericht', '' ),
        "menu_name" => __( 'Tourenberichte', '' ),
        "all_items" => __( 'Tourenberichte', '' ),
        "add_new" => __( 'Tourenbericht erfassen', '' ),
        "add_new_item" => __( 'Neue Tourenbericht erfassen', '' ),
        "edit_item" => __( 'Tourenbericht anpassen', '' ),
        "new_item" => __( 'Neue Tourenbericht', '' ),
        "view_item" => __( 'Tourenbericht ansehen', '' ),
        "view_items" => __( 'Tourenberichte ansehen', '' ),
        "search_items" => __( 'Tourenbericht suchen', '' ),
    );

    $args = array(
        "label" => __( 'Tourenberichte', '' ),
        "labels" => $labels,
        "description" => "Tourenberichte des Bergclubs Bern",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => ['tour', 'tourenberichte'],
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "tourenberichteverwaltung", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail", "custom-fields" ),
        "menu_position" => 5, //below Posts according to https://codex.wordpress.org/Function_Reference/register_post_type,
    );

    register_post_type( BCB_CUSTOM_POST_TYPE_Tourenberichte, $args );
}

add_action( 'init', 'bcb_register_my_tourenberichteverwaltung' );


function bcb_create_new_metabox_context_tourenberichte( $post ) {
    do_meta_boxes( null, 'bcb-metabox-holder-tourenberichte', $post );
}

add_action( 'edit_form_after_title', 'bcb_create_new_metabox_context_tourenberichte' );