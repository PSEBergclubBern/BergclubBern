<?php

use BergclubPlugin\MVC\Menu;

//creating the admin menu
$assets = [
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
    plugins_url('assets/css/app.css', __FILE__),
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
    plugins_url('assets/js/de/datepicker.js', __FILE__),
    plugins_url('assets/js/export.js', __FILE__),
];

$adminMenu = new Menu('Export', 'export', 'BergclubPlugin\\Export\\Controllers\\MainController', $assets, 'dashicons-download');

//defining the (public) pdf calendar url
define('BCB_CALENDAR_URL', $adminMenu->getUrl() . '&download=calendar.pdf');

/**
 * Will be called from TagHelper if the content contains [bcb_calendar_link]
 *
 * @return string the value with which [bcb_calendar_link] should be replaced.
 */
function bcb_calendar_link()
{
    return '<a target="_blank" href="' . BCB_CALENDAR_URL . '">';
}

/**
 * Will be called from TagHelper if the content contains [/bcb_calendar_link]
 *
 * @return string the value with which [/bcb_calendar_link] should be replaced.
 */
function bcb_calendar_link_end()
{
    return '</a>';
}

//register the two functions above
\BergclubPlugin\TagHelper::addTag('bcb_calendar_link', 'bcb_calendar_link');
\BergclubPlugin\TagHelper::addTag('bcb_calendar_link_end', 'bcb_calendar_link_end');

//register Download::run to be called when the wp init function is called.
add_action('init', [new \BergclubPlugin\Export\Download(), 'run']);