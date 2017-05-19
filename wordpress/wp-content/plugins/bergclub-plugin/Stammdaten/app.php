<?php
use BergclubPlugin\MVC\Menu;
use BergclubPlugin\MVC\SubMenu;

$assets = [
    plugins_url('assets/css/app.css', __FILE__),
];

/**
 *  create main menu and sub menus for "Stammdaten"
 */
$adminMenu = new Menu('Stammdaten', 'manage_options', 'BergclubPlugin\\Stammdaten\\MitgliederbeitraegeController', $assets, 'dashicons-admin-tools');
$adminMenu->addSubMenu(new SubMenu('Mitgliederbeiträge', 'manage_options', 'BergclubPlugin\\Stammdaten\\MitgliederbeitraegeController', $assets));
$adminMenu->addSubMenu(new SubMenu('Tourenarten', 'manage_options', 'BergclubPlugin\\Stammdaten\\TourenartenController', $assets));
$adminMenu->addSubMenu(new SubMenu('Schwierigkeitsgrade', 'manage_options', 'BergclubPlugin\\Stammdaten\\SchwierigkeitsgradeController', $assets));
