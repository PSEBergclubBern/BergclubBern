<?php

//Creates initial pages with dummy content
function bcb_add_pages(){
    $page = get_page_by_path('uber-uns');
    if (!$page) {
        $uber_uns_page_content = 'Text über uns';
        wp_insert_post(bcb_get_page_data('Über uns', $uber_uns_page_content, 'uber-uns'));
    }

    $page = get_page_by_path('portrait');
    if (!$page) {
        $portrait_page_content = 'Wieder irgendwelcher Text über den Bergclub';
        wp_insert_post(bcb_get_page_data('Portrait', $portrait_page_content, 'portrait'));
    }

    $page = get_page_by_path('vorstand');
    if (!$page) {
        $vorstand_page_content = 'Hier kommen alle Vorstandsmitglieder';
        wp_insert_post(bcb_get_page_data('Vorstand', $vorstand_page_content, 'vorstand'));
    }

    $page = get_page_by_path('statuten');
    if (!$page) {
        $statuten_page_title = 'Statuten';
        $statuten_page_content = 'Dokumente';
        wp_insert_post(bcb_get_page_data('Statuten', $statuten_page_content, 'statuten'));
    }

    $page = get_page_by_path('touren');
    if (!$page) {
        $touren_page_content = 'Tourenberichte';
        wp_insert_post(bcb_get_page_data('Touren', $touren_page_content, 'touren'));
    }

    $page = get_page_by_path('mitteilungen');
    if (!$page) {
        $mitteilungen_page_content = 'Mitteilungen';
        wp_insert_post(bcb_get_page_data('Mitteilungen', $mitteilungen_page_content, 'mitteilungen'));
    }

    $page = get_page_by_path('service');
    if (!$page) {
        $service_page_content = 'Have you tried turn it off and on again?';
        wp_insert_post(bcb_get_page_data('Service', $service_page_content, 'service'));
    }

    $page = get_page_by_path('login');
    if (!$page) {
        $login_page_content = 'Log yourself in';
        wp_insert_post(bcb_get_page_data('Login', $login_page_content, 'login'));
    }
}

function bcb_get_page_data($title, $content, $slug){
    return array(
        'post_type' => 'page',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_slug' => 'login'
    );
}

add_filter( 'init', 'bcb_add_pages' );

//Creates Header Navigation Bar and adds page-links
function bcb_add_header_navigation() {
    // Check if the menu exists
    $menu_name = 'Header Navigation';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    /*
     * Deletes the main menu, used during menu development.
     * Comment out, when finished.
     */
    /*
    if($menu_exists){
        wp_delete_nav_menu( $menu_name );
        $menu_exists = false;
    }
    */

    // If it doesn't exist, let's create it.
    if( !$menu_exists){
        $menu_id = wp_create_nav_menu($menu_name);

        // Set up default menu items
        $parentId = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Über uns'),
            'menu-item-position' => 1,
            'menu-item-classes' => 'ueber-uns',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('portrait', 2, $parentId));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('vorstand', 3, $parentId));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('statuten', 4, $parentId));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('touren', 5));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('mitteilungen', 6));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('service', 7));

        wp_update_nav_menu_item($menu_id, 0, bcb_get_nav_menu_data('login', 8));
    }
}

//Helper function to create array Menu Data for an item
function bcb_get_nav_menu_data($path, $position, $parentId = 0){
    return array(
        'menu-item-object-id' => get_page_by_path($path)->ID,
        'menu-item-parent-id' => $parentId,
        'menu-item-position'  => $position,
        'menu-item-object' => 'page',
        'menu-item-type'      => 'post_type',
        'menu-item-status'    => 'publish'
    );
}

add_filter( 'init', 'bcb_add_header_navigation' );