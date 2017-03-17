<?php

use BergclubPlugin\MVC\Menu;
use BergclubPlugin\MVC\SubMenu;

$assets = [
    '//cdn.datatables.net/v/dt/dt-1.10.13/r-2.1.1/datatables.min.css',
    plugins_url('assets/css/app.css', __FILE__),
    '//cdn.datatables.net/v/dt/dt-1.10.13/r-2.1.1/datatables.min.js'
];

$adminMenu = new Menu('Adressen', 'manage_options', 'BergclubPlugin\\Adressverwaltung\\Controllers\\MainController', $assets, 'dashicons-admin-users');