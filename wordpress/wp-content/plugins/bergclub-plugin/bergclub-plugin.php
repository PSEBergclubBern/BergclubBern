<?php
/**
 * @package Bergclub-Plugin
 */
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
//if ( !function_exists( 'add_action' ) ) {
//echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
//exit;
//}

require_once __DIR__ . '/vendor/autoload.php';

if( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'bergclub import', 'BergclubPlugin\Commands\Import' );
    WP_CLI::add_command( 'bergclub mitteilung', 'BergclubPlugin\Commands\Mitteilung' );
}

/*
 * Plugin activation
 */
register_activation_hook(__FILE__, 'bcb_activate_plugin');

function bcb_activate_plugin(){
    include_sub_directory_file('activate.php');
}

/*
 * Plugin deactivation
 */
register_deactivation_hook(__FILE__, 'bcb_deactivate_plugin');

function bcb_deactivate_plugin(){
    include_sub_directory_file('deactivate.php');
}

/*
 * Ensure session is started
 */
add_action('init','bcb_register_session');

function bcb_register_session(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Looks trough all first level folders in plugins sub folder and includes the given file name if found.
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

/*
 * include the different apps (plugin sub folder)
 */
include_sub_directory_file('app.php');

add_action('admin_enqueue_scripts', ['BergclubPlugin\\AssetHelper', 'getAssets']);
