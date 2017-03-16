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

// Hook for plugin activation
register_activation_hook(__FILE__, 'bcb_activate_plugin');

//Hook for plugin deactivation
register_deactivation_hook(__FILE__, 'bcb_deactivate_plugin');

function bcb_activate_plugin(){
    //include all activate.php from sub folders
    include_sub_directory_file('activate.php');
}

function bcb_deactivate_plugin(){
    //include all deactivate.php from sub folders
    include_sub_directory_file('deactivate.php');
}

/**
 * @param string $fileName the fileName to look for in all subfolders, will be included if found.
 */
function include_sub_directory_file($fileName){
    $files = scandir(__DIR__);
    foreach($files as $item) {
        if ($item != "." && $item != ".." && is_dir(__DIR__ . '/' . $item) && file_exists(__DIR__ . '/' . $item . '/' . $fileName)) {
            require_once __DIR__ . '/' . $item . '/' . $fileName;
        }
    }
}

//include all app.php from sub folders
include_sub_directory_file('app.php');