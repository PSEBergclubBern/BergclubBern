<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17.03.2017
 * Time: 10:09
 */


function register_my_tourenverwaltung() {

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
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "tourenverwaltung", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail", "custom-fields" ),
    );

    register_post_type( "tourenverwaltung", $args );
}

add_action( 'init', 'register_my_tourenverwaltung' );