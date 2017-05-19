<?php

use BergclubPlugin\MVC\Menu;

$assets = [
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
    plugins_url('assets/css/app.css', __FILE__),
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
    plugins_url('assets/js/de/datepicker.js', __FILE__),
    plugins_url('assets/js/export.js', __FILE__),
];

$adminMenu = new Menu('Export', 'export', 'BergclubPlugin\\Export\\Controllers\\MainController', $assets, 'dashicons-download');

define('BCB_CALENDAR_URL', $adminMenu->getUrl() . '&download=calendar.pdf');

function bcb_calendar_link()
{
    return '<a target="_blank" href="' . BCB_CALENDAR_URL . '">';
}

function bcb_calendar_link_end()
{
    return '</a>';
}

\BergclubPlugin\TagHelper::addTag('bcb_calendar_link', 'bcb_calendar_link');
\BergclubPlugin\TagHelper::addTag('bcb_calendar_link_end', 'bcb_calendar_link_end');

add_action('init', [new \BergclubPlugin\Export\Download(), 'run']);