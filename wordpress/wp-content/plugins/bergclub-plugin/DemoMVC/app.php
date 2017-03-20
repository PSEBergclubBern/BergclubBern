<?php

use BergclubPlugin\MVC\Menu;
use BergclubPlugin\MVC\SubMenu;

$assets = [
    plugins_url('assets/css/demomvc.css', __FILE__),
    plugins_url('assets/js/demomvc.js', __FILE__),
];

$adminMenu = new Menu('MVC Demo', 'manage_options', 'BergclubPlugin\\DemoMVC\\Controllers\\MainController', $assets, 'dashicons-admin-users');
$adminMenu->addSubMenu(new SubMenu('Formular', 'manage_options', 'BergclubPlugin\\DemoMVC\\Controllers\\FormController', $assets));