<?php
/**
 * Registers the custom post types
 */
function bcb_register_custom_post_types() {

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
        "supports" => array( "title", "editor", "thumbnail" ),
        "menu_position" => 5,
        "rewrite" => ['slug' => 'tourenprogramm'],
    );

    register_post_type( "touren", $args );

    //disable editor for post type 'touren';
    remove_post_type_support( 'touren', 'editor' );


    /**
     * Post Type: Tourenberichte.
     */

    $labels = array(
        "name" => __( 'Tourenberichte', '' ),
        "singular_name" => __( 'Tourenbericht', '' ),
        "menu_name" => __( 'Tourenberichte', '' ),
        "all_items" => __( 'Tourenberichte', '' ),
        "add_new" => __( 'Tourenbericht erfassen', '' ),
        "add_new_item" => __( 'Neuen Tourenbericht erfassen', '' ),
        "edit_item" => __( 'Tourenbericht anpassen', '' ),
        "new_item" => __( 'Neuer Tourenbericht', '' ),
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
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => ['tourenbericht', 'tourenberichte'],
        "map_meta_cap" => true,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail" ),
        "menu_position" => 6,
    );

    register_post_type( "tourenberichte", $args );

    remove_post_type_support( 'tourenberichte', 'title' );
}

add_action( 'init', 'bcb_register_custom_post_types' );

function bcb_touren_columns($columns) {
    return array_merge( $columns,
        ['type' => 'Art', 'dateFrom' => 'Von', 'dateTo' => 'Bis']
    );
}

function bcb_touren_sortable_columns($columns){
    return array_merge( $columns,
        ['type' => 'type', 'dateFrom' => 'dateFrom', 'dateTo' => 'dateTo']
    );
}

function bcb_touren_custom_columns($column, $postId){
    echo bcb_touren_meta($postId, $column);
}

function bcb_pre_get_posts(WP_Query $query){
    if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

        switch( $orderby ) {
            case 'type':
                $query->set( 'meta_key', '_type' );
                $query->set( 'orderby', 'meta_value' );
                break;
            case 'dateFrom':
                $query->set( 'meta_key', '_dateFromDB' );
                $query->set( 'orderby', 'meta_value' );
                break;
            case 'dateTo':
                $query->set( 'meta_key', '_dateToDB' );
                $query->set( 'orderby', 'meta_value' );
                break;
        }

    }
}

add_filter('manage_touren_posts_columns' , 'bcb_touren_columns');
add_filter('manage_edit-touren_sortable_columns', 'bcb_touren_sortable_columns');
add_action('manage_touren_posts_custom_column' , 'bcb_touren_custom_columns', 10, 2);
add_action( 'pre_get_posts', 'bcb_pre_get_posts', 1 );