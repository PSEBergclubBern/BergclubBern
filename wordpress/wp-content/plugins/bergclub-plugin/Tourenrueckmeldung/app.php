<?php

use BergclubPlugin\MVC\Menu;

$assets = [
    plugins_url('assets/css/app.css', __FILE__),
];

// add menu for "Tourenrückmeldungen"
$adminMenu = new Menu('Rückmeldungen', 'rueckmeldungen', 'BergclubPlugin\\Tourenrueckmeldung\\Controllers\\MainController', $assets, 'dashicons-clipboard');