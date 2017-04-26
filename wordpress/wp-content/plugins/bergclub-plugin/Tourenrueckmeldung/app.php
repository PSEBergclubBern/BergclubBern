<?php

use BergclubPlugin\MVC\Menu;


/*
$assets = [
    '//cdn.datatables.net/v/dt/dt-1.10.13/r-2.1.1/datatables.min.css',
    '//cdn.jsdelivr.net/sweetalert2/6.4.4/sweetalert2.min.css',
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
    plugins_url('assets/css/app.css', __FILE__),
    '//cdn.datatables.net/v/dt/dt-1.10.13/r-2.1.1/datatables.min.js',
    '//cdn.jsdelivr.net/sweetalert2/6.4.4/sweetalert2.min.js',
    '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
    plugins_url('assets/js/de/datepicker.js', __FILE__),
];
*/

$assets = [
    plugins_url('assets/css/app.css', __FILE__),
];


$adminMenu = new Menu('Rückmeldungen', 'rueckmeldungen', 'BergclubPlugin\\Tourenrueckmeldung\\Controllers\\MainController', $assets, 'dashicons-clipboard');