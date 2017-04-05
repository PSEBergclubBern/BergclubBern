<?php
use BergclubPlugin\MVC\Models\Option;

//remove sample page
$page = get_page_by_path('sample-page');
if($page) {
    wp_delete_post($page->ID, true);
}

//remove sample post
$posts = get_posts(['name' => 'hello-world']);
print_r($posts);
if(is_array($posts) && isset($posts[0])){
    wp_delete_post($posts[0]->ID, true);
}

//add the category 'mitteilungen'
$category = get_category_by_slug('mitteilungen');
if (!$category) {
    wp_insert_category([
        'cat_name' => 'Mitteilungen',
        'category_description' => 'In dieser Kategorie werden alle Mitteilungen erfasst',
        'category_nicename' => 'mitteilungen',
        'taxonomy' => 'category',
    ]);
}

//set 'mitteilungen' as default category
Option::set('default_category', get_category_by_slug('mitteilungen')->term_id);

//creates WP pages from given array
function bcb_create_pages($pages)
{
    foreach ($pages as $slug => $page) {
        $exists = get_page_by_path($slug);
        if (!$exists) {
            wp_insert_post([
                'post_type' => 'page',
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_slug' => $slug,
            ]);
        }
    }
}

//load pages data and create entries.
$pages = json_decode(file_get_contents(__DIR__ . '/data/pages.json'), true);
bcb_create_pages($pages);

function bcb_create_menu_item($menuId, $parentId, $slug, $title, $position, $page){
    $args = [
        'menu-item-title' => $title,
        'menu-item-parent-id' => $parentId,
        'menu-item-position' => $position,
        'menu-item-classes' => $slug,
        'menu-item-status' => 'publish',
    ];

    if(!empty($page)){
        $wpPage = get_page_by_path($page);
        if($wpPage) {
            $args['menu-item-type'] = 'post_type';
            $args['menu-item-object'] = 'page';
            $args['menu-item-object-id'] = $wpPage->ID;
        }
    }
    return wp_update_nav_menu_item($menuId, 0, $args);
}

function bcb_create_menu($menu){
    //delete menu if exists
    wp_delete_nav_menu('header-navigation');

    $menuId = wp_create_nav_menu('header-navigation');
    $position = 0;
    foreach ($menu as $slug => $menuItem) {
        $parentId = 0;
        $position++;
        $page = null;
        if (isset($menuItem['page'])) {
            $page = $menuItem['page'];
        }

        $parentId = bcb_create_menu_item($menuId, $parentId, $slug, $menuItem['title'], $position, $page);
        if(isset($menuItem['sub-menu'])){
            $subPosition = 0;
            foreach ($menuItem['sub-menu'] as $subSlug => $subMenuItem){
                $subPosition++;
                $slug .= "-" . $subSlug;
                $page = null;
                if (isset($subMenuItem['page'])) {
                    $page = $subMenuItem['page'];
                }
                bcb_create_menu_item($menuId, $parentId, $slug, $subMenuItem['title'], $subPosition, $page);
            }
        }
    }
}

$menu = json_decode(file_get_contents(__DIR__ . '/data/menu.json'), true);
bcb_create_menu($menu);