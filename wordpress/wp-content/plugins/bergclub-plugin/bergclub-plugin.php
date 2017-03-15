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
 * Scan for init files in sub folders and include them.
 */
$files = scandir(__DIR__);
foreach($files as $item) {
    if ($item != "." && $item != ".." && file_exists(__DIR__ . '/' . $item . '/init.php')) {
        require_once __DIR__ . '/' . $item . '/init.php';
    }
}
