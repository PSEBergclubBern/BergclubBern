<?php
use BergclubPlugin\MVC\Models\Option;

//delete the main menu
wp_delete_nav_menu('header-navigation');

//delete WP pages from given array
function bcb_delete_pages($pages)
{
    foreach ($pages as $slug => $item) {
        $page = get_page_by_path($slug);
        if($page) {
            wp_delete_post($page->ID, true);
        }
    }
}

//load pages data and delete entries.
$pages = json_decode(file_get_contents(__DIR__ . '/data/pages.json'), true);
bcb_delete_pages($pages);


//reset default category
Option::set('default_category', 1);

//delete category 'mitteilungen'
wp_delete_category(get_category_by_slug('mitteilungen')->term_id);

//remove the background images information (WP Option)
Option::remove('background_images');