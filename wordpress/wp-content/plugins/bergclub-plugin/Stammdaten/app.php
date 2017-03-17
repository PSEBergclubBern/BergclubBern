<?php
use BergclubPlugin\MVC\Menu;
use BergclubPlugin\MVC\SubMenu;

$adminMenu = new Menu('Stammdaten', 'manage_options', 'BergclubPlugin\\Stammdaten\\MitgliederbeitraegeController', [], 'dashicons-admin-tools');
$adminMenu->addSubMenu(new SubMenu('Mitgliederbeiträge', 'manage_options', 'BergclubPlugin\\Stammdaten\\MitgliederbeitraegeController'));
$adminMenu->addSubMenu(new SubMenu('Tourenarten', 'manage_options', 'BergclubPlugin\\Stammdaten\\TourenartenController'));
$adminMenu->addSubMenu(new SubMenu('Schwierigkeitsgrade', 'manage_options', 'BergclubPlugin\\Stammdaten\\SchwierigkeitsgradeController'));
