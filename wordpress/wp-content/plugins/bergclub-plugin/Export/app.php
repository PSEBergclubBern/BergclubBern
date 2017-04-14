<?php

use BergclubPlugin\MVC\Menu;

$assets = [
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
    plugins_url('assets/css/app.css', __FILE__),
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
    plugins_url('assets/js/de/datepicker.js', __FILE__),
];

$adminMenu = new Menu('Export', 'export', 'BergclubPlugin\\Export\\Controllers\\MainController', $assets, 'dashicons-download');

$download = new \BergclubPlugin\Export\Download();
add_action( 'init', [$download, 'detectDownload'] );