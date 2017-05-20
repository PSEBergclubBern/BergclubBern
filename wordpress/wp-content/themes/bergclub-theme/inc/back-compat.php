<?php
/**
 * Bergclub Bern back compat functionality
 *
 * Prevents Berclub Bern theme from running on WordPress versions prior to 4.1,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.1.
 *
 * @package WordPress
 * @subpackage Bergclub_Bern
 */

/**
 * Prevent switching to Bergclub Bern on old versions of WordPress.
 *
 * Switches to the default theme.
 */
function bcb_switch_theme()
{
    switch_theme(WP_DEFAULT_THEME, WP_DEFAULT_THEME);
    unset($_GET['activated']);
    add_action('admin_notices', 'bcb_upgrade_notice');
}

add_action('after_switch_theme', 'bcb_switch_theme');

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Bergclub Bern theme on WordPress versions prior to 4.1.
 */
function bcb_upgrade_notice()
{
    $message = sprintf(__('Bergclub Bern requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'bcb'), $GLOBALS['wp_version']);
    printf('<div class="error"><p>%s</p></div>', $message);
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 4.1.
 */
function bcb_customize()
{
    wp_die(sprintf(__('Berglub Bern requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'bcb'), $GLOBALS['wp_version']), '', array(
        'back_link' => true,
    ));
}

add_action('load-customize.php', 'bcb_customize');

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.1.
 */
function bcb_preview()
{
    if (isset($_GET['preview'])) {
        wp_die(sprintf(__('Bergclub Bern requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'bcb'), $GLOBALS['wp_version']));
    }
}

add_action('template_redirect', 'bcb_preview');
