<?php
/*
Plugin Name: Bergclub-Plugin
Plugin URI: https://github.com/PSEBergclubBern/BergclubPlugin
Description: This Plugin is designed to modify an existing wordpress installation to the needs of the customer
Version: 1.0
Author: PSE
Author URI: http://unibe.ch
License: GPLv2 or later
Text Domain: unibe.ch
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

use BergclubPlugin\MVC\Models\User;

require_once __DIR__ . '/vendor/autoload.php';

// if WP_CLI is available add the custom bergclup commands.
if( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'bergclub import', 'BergclubPlugin\Commands\Import' );
    WP_CLI::add_command( 'bergclub mitteilung', 'BergclubPlugin\Commands\Mitteilung' );
    WP_CLI::add_command( 'bergclub pseudo-users', 'BergclubPlugin\Commands\PseudoUser' );
}

// register our activation method
register_activation_hook(__FILE__, 'bcb_activate_plugin');

/**
 * Called on plugin activation, includes activate.php files in subdirectories.
 */
function bcb_activate_plugin(){
    include_sub_directory_file('activate.php');
}

// register our deactivation method
register_deactivation_hook(__FILE__, 'bcb_deactivate_plugin');

/**
 * Called on plugin deactivation, includes deactivate.php files in subdirectories.
 */
function bcb_deactivate_plugin(){
    include_sub_directory_file('deactivate.php');
}

// register our bcb_register_session function to WP init function
add_action('init','bcb_register_session');

/**
 * Ensures that PHP session is started
 */
function bcb_register_session(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// register our bcb_remove_admin_pages function to WP admin menu function
add_action( 'admin_menu', 'bcb_remove_admin_pages' );

/**
 * Removes admin pages we don't want to show if the current user has not the administrator role assigned.
 */
function bcb_remove_admin_pages() {
    $user = wp_get_current_user();
    if(!in_array('administrator', (array) $user->roles)) {
        remove_menu_page('profile.php');
        remove_submenu_page('users.php', 'profile.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');
    }
}

// register our AssetHelper::getAssets method to the WP admin_enqueue_scripts function
add_action('admin_enqueue_scripts', ['BergclubPlugin\\AssetHelper', 'getAssets']);

/*
 * Returns a special mail link for the admin email (main email for the system), which will be encoded in the Bergclub theme.
 */
function bcb_email_main(){
    return bcb_email(get_option('admin_email'));
}

/*
 * Returns a special mail link for the given email, which will be encoded in the Bergclub theme.
 */
function bcb_email($email){
    return "<a class='email' data-id='" . base64_encode($email) . "'></a>";
}

// assigning the content tag [bcb_email] to our bcb_email_main function
\BergclubPlugin\TagHelper::addTag('bcb_email', 'bcb_email_main');

// registers our bcb_content_filter function to the WP the_content filter function
add_filter( 'the_content', 'bcb_content_filter' );

/*
 * Checks the given content for tag keys registered with `TagHelper`.
 * If the key is registered it will replace it with the content the registered method/function returns.
 * Otherwise it will remove the tag from the content.
 */
function bcb_content_filter($content){
    foreach(\BergclubPlugin\TagHelper::getKeys() as $key){
        if(strstr($content, '[' . $key . ']')){
            $content = str_replace('[' . $key . ']', \BergclubPlugin\TagHelper::getTag($key), $content);
        }else{
            $content = str_replace('[' . $key . ']', '', $content);
        }

        if(strstr($content, '[/' . $key . ']')){
            $content = str_replace('[/' . $key . ']', \BergclubPlugin\TagHelper::getTag($key . "_end"), $content);
        }else{
            $content = str_replace('[/' . $key . ']', '', $content);
        }
    }
    return $content;
}

// registers our bcb_change_post_label function to WP admin_menu function
add_action( 'admin_menu', 'bcb_change_post_label' );

/**
 * Overrides "Beiträge" admin menü with "Mitteilungen".
 */
function bcb_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Mitteilungen';
    $submenu['edit.php'][5][0] = 'Mitteilungen';
    $submenu['edit.php'][10][0] = 'Mitteilung hinzufügen';
}

// registers our bcb_change_post_object function to WP init function
add_action( 'init', 'bcb_change_post_object' );

/**
 * Overriding the default post type "Beiträge" with "Mitteilungen"
 */
function bcb_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Mitteilungen';
    $labels->singular_name = 'Mitteilung';
    $labels->add_new = 'Mitteilung hinzufügen';
    $labels->add_new_item = 'Mitteilung hinzufügen';
    $labels->edit_item = 'Mitteilung anpassen';
    $labels->new_item = 'Mitteilung';
    $labels->view_item = 'Mitteilung anschauen';
    $labels->search_items = 'Mitteilung suchen';
    $labels->not_found = 'Keine Mitteilungen gefunden';
    $labels->not_found_in_trash = 'Keine Mitteilungen im Papierkorb gefunden';
    $labels->all_items = 'Alle Mitteilungen';
    $labels->menu_name = 'Mitteilungen';
    $labels->name_admin_bar = 'Mitteilungen';
}

// registers our bcb_remove_unneeded_metabox function to WP add_meta_boxes function
add_action( 'add_meta_boxes', 'bcb_remove_unneeded_meta_box' );

/**
 * Removes the meta boxes for changing the post slug and for the post image.
 */
function bcb_remove_unneeded_meta_box() {
    remove_meta_box( 'slugdiv', 'post', 'normal' );
    remove_meta_box( 'slugdiv', 'page', 'normal' );
    remove_meta_box( 'slugdiv', 'touren', 'normal' );
    remove_meta_box( 'slugdiv', 'tourenberichte', 'normal' );
    remove_meta_box( 'postimagediv', 'post', 'normal' );
    remove_meta_box( 'postimagediv', 'page', 'normal' );
    remove_meta_box( 'postimagediv', 'touren', 'normal' );
    remove_meta_box( 'postimagediv', 'tourenberichte', 'normal' );
}

/**
 * Used to receive formatted meta values for the custom post type "Touren"
 *
 * @param int $postId the id of the WP Post
 * @param string $metaKey the key for the needed meta value
 * @return string the formatted value for the given key
 */
function bcb_touren_meta($postId, $metaKey){
    $method = "get" . strtoupper(substr($metaKey, 0, 1)) . substr($metaKey, 1);
    return \BergclubPlugin\TourenHelper::$method($postId);
}


/**
 * Loops trough all first level folders in plugins sub folder and includes the given file name if found.
 *
 * @param string $fileName the file name to look for.
 */
function include_sub_directory_file($fileName){
    $files = scandir(__DIR__);
    foreach($files as $item) {
        if ($item != "." && $item != ".." && is_dir(__DIR__ . '/' . $item) && file_exists(__DIR__ . '/' . $item . '/' . $fileName)) {
            require_once __DIR__ . '/' . $item . '/' . $fileName;
        }
    }
}


// include app.php files in subdirectories.
include_sub_directory_file('app.php');