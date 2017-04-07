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

/*
 * Returns a special mail link for bergclub@bergclub.ch, which will be encoded in the Bergclub theme.
 */
function bcb_email_main(){
    return bcb_email('bergclub@bergclub.ch');
}

/*
 * Returns a special mail link for the given email, which will be encoded in the Bergclub theme.
 */
function bcb_email($email){
    return "<a class='email' data-id='" . base64_encode($email) . "'></a>";
}

/*
 * Adds the content tag [bcb_email] and the corresponding function.
 * [bcb_email] can be used in every post or page content.
 */
\BergclubPlugin\TagHelper::addTag('bcb_email', 'bcb_email_main');


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
    }
    return $content;
}
add_filter( 'the_content', 'bcb_content_filter' );