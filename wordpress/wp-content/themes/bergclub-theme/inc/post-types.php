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
        "show_in_nav_menu" => true,
        "show_in_menu" => true,
        "show_in_admin_bar" => true,
        "exclude_from_search" => false,
        "capability_type" => "tour",
        "capabilities" => [
            "create_posts" => "create_tour",
            "publish_posts" => "publish_tour",
            "edit_posts" => "edit_tour",
            "edit_others_posts" => "edit_others_tour",
            "delete_posts" => "delete_tour",
            "delete_others_posts" => "delete_others_tour",
            "read_private_posts" => "read_private_tour",
            "edit_post" => "edit_tour",
            "delete_post" => "delete_tour",
            "read_post" => "read_tour",
        ],
        "map_meta_cap" => false,
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
        "capability_type" => "tourenbericht",
        "capabilities" => [
            "create_posts" => "create_tourenbericht",
            "publish_posts" => "publish_tourenbericht",
            "edit_posts" => "edit_tourenbericht",
            "edit_others_posts" => "edit_others_tourenbericht",
            "delete_posts" => "delete_tourenbericht",
            "delete_others_posts" => "delete_others_tourenbericht",
            "read_private_posts" => "read_private_tourenbericht",
            "edit_post" => "edit_tourenbericht",
            "delete_post" => "delete_tourenbericht",
            "read_post" => "read_tourenbericht",
        ],
        "map_meta_cap" => false,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array( "title", "editor" ),
        "menu_position" => 6,
    );

    register_post_type( "tourenberichte", $args );

    remove_post_type_support( 'tourenberichte', 'title' );
}

add_action( 'init', 'bcb_register_custom_post_types' );

/**
 * Maps the custom capabilites for the custom post list in admin
 * @param $caps
 * @param $cap
 * @param $user_id
 * @param $args
 * @return array
 */
function bcb_map_meta_cap( $caps, $cap, $user_id, $args ) {
    $post = null;
    $post_type = null;

    $checkCaps = [
        'tour',
        'tourenbericht',
    ];

    $capArr = explode("_", $cap);

    /* If editing, deleting, or reading a tour, get the post and post type object. */
    if (isset($args[0]) && count($capArr) == 2 && in_array($capArr[1], $checkCaps) && ($capArr[0] == 'edit' || $capArr[0] = 'delete' || $capArr[0] = 'read') ) {
        $post = get_post( $args[0] );
        $post_type = get_post_type_object( $post->post_type );
        $caps = array();
    }

    if($post) {
        /* If editing a tour, assign the required capability. */
        if ($capArr[0] == 'edit') {
            if ($user_id == $post->post_author)
                $caps[] = $post_type->cap->edit_posts;
            else
                $caps[] = $post_type->cap->edit_others_posts;
        } /* If deleting a tour, assign the required capability. */
        elseif ($capArr[0] == 'delete') {
            if ($user_id == $post->post_author)
                $caps[] = $post_type->cap->delete_posts;
            else
                $caps[] = $post_type->cap->delete_others_posts;
        } /* If reading a private tour, assign the required capability. */
        elseif ($capArr[0] == 'read') {

            if ('private' != $post->post_status)
                $caps[] = 'read';
            elseif ($user_id == $post->post_author)
                $caps[] = 'read';
            else
                $caps[] = $post_type->cap->read_private_posts;
        }
    }

    /* Return the capabilities required by the user. */
    return $caps;
}

add_filter( 'map_meta_cap', 'bcb_map_meta_cap', 10, 4 );

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
    if (is_admin() && $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

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

    if(!is_admin() && isset($query->query['post_type']) && ($query->query['post_type'] == 'touren' || $query->query['post_type'] == 'tourenberichte') && !is_singular()){
        $currentTourenart = '';
        if(isset($_GET['type'])){
            $currentTourenart = $_GET['type'];
        }

        if(bcb_is_jugend()) {
            if($query->query['post_type'] == 'tourenberichte') {
                $query->set('meta_query', [
                    'relation' => 'OR',
                    [
                        'key' => '_isYouth',
                        'value' => '1',
                        'compare' => '='
                    ],
                    [
                        'key' => '_isYouth',
                        'value' => '2',
                        'compare' => '='
                    ],
                ]);
            }else{
                $query->set('order', 'ASC');
                $query->set('orderby', '_dateFromDB');

                if(empty($currentTourenart)) {
                    $query->set('meta_query', [
                        'relation' => 'AND',
                        [
                            'key' => '_dateFromDB',
                            'value' => date('Y-m-d'),
                            'type' => 'DATE',
                            'compare' => '>='
                        ],
                        [
                            'relation' => 'OR',
                            [
                                'key' => '_isYouth',
                                'value' => '1',
                                'compare' => '='
                            ],
                            [
                                'key' => '_isYouth',
                                'value' => '2',
                                'compare' => '='
                            ],
                        ],
                    ]);
                }else{
                    $query->set('meta_query', [
                        'relation' => 'AND',
                        [
                            'key' => '_dateFromDB',
                            'value' => date('Y-m-d'),
                            'type' => 'DATE',
                            'compare' => '>='
                        ],
                        [
                            'key' => '_type',
                            'value' => $currentTourenart,
                            'compare' => '='
                        ],
                        [
                            'relation' => 'OR',
                            [
                                'key' => '_isYouth',
                                'value' => '1',
                                'compare' => '='
                            ],
                            [
                                'key' => '_isYouth',
                                'value' => '2',
                                'compare' => '='
                            ],
                        ],
                    ]);
                }
            }
        }else{
            if($query->query['post_type'] == 'tourenberichte') {
                $query->set('meta_query', [
                    'relation' => 'OR',
                    [
                        'key' => '_isYouth',
                        'compare' => 'NOT EXISTS'
                    ],
                    [
                        'key' => '_isYouth',
                        'value' => '0',
                        'compare' => '='
                    ],
                    [
                        'key' => '_isYouth',
                        'value' => '2',
                        'compare' => '='
                    ],
                ]);
            }else{
                $query->set('order', 'ASC');
                $query->set('orderby', '_dateFromDB');

                if(empty($currentTourenart)) {
                    $query->set('meta_query', [
                        'relation' => 'AND',
                        [
                            'key' => '_dateFromDB',
                            'value' => date('Y-m-d'),
                            'type' => 'DATE',
                            'compare' => '>='
                        ],
                        [
                            'relation' => 'OR',
                            [
                                'key' => '_isYouth',
                                'value' => '0',
                                'compare' => '='
                            ],
                            [
                                'key' => '_isYouth',
                                'value' => '2',
                                'compare' => '='
                            ],
                        ],
                    ]);
                }else{
                    $query->set('meta_query', [
                        'relation' => 'AND',
                        [
                            'key' => '_dateFromDB',
                            'value' => date('Y-m-d'),
                            'type' => 'DATE',
                            'compare' => '>='
                        ],
                        [
                            'key' => '_type',
                            'value' => $currentTourenart,
                            'compare' => '='
                        ],
                        [
                            'relation' => 'OR',
                            [
                                'key' => '_isYouth',
                                'value' => '0',
                                'compare' => '='
                            ],
                            [
                                'key' => '_isYouth',
                                'value' => '2',
                                'compare' => '='
                            ],
                        ],
                    ]);
                }
            }
        }
    }

    return $query;
}

add_filter('manage_touren_posts_columns' , 'bcb_touren_columns');
add_filter('manage_edit-touren_sortable_columns', 'bcb_touren_sortable_columns');
add_action('manage_touren_posts_custom_column' , 'bcb_touren_custom_columns', 10, 2);
add_action( 'pre_get_posts', 'bcb_pre_get_posts', 1 );