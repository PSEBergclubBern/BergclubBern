<?php
use BergclubPlugin\MVC\Models\User;
use BergclubPlugin\MVC\Models\Role;

$role = new Role(Role::TYPE_ADDRESS, 'institution', 'Institution');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'inserent', 'Inserent');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'interessent', 'Interessent');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'interessent_jugend', 'Interessent Jugend');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied', 'Aktivmitglied');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied_jugend', 'Aktivmitglied Jugend');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'ehrenmitglied', 'Ehrenmitglied');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'ehemalig', 'Ehemalig');
$role->addCapability('read', false);
$role->save();

$role = new Role(Role::TYPE_ADDRESS, 'freimitglied', 'Freimitglied');
$role->addCapability('read', false);
$role->save();

/**
 * Load array with Functionary roles and assigned capabilities.
 */
require_once 'activate_functionary_roles.php';

foreach($functionaryRoles as $slug => $item){
    $role = new Role(Role::TYPE_FUNCTIONARY, $slug, $item['name']);
    $role->setCapabilities($item['capabilities']);
    $role->save();
}

/*
 * The custom role `internet` will be the assigned to the administrator of Bergclub Bern, so we add all capabilities of
 * our functionary roles and the default `administrator` role except for the capabilities needed for user management,
 * because user management will be done in "Adressverwaltung".
 */
$roleInternet = new Role(Role::TYPE_FUNCTIONARY, 'internet', 'Internet');

$roles = Role::findByType(Role::TYPE_FUNCTIONARY);
foreach($roles as $role){
    /* @var Role $role */
    foreach($role->getCapabilities() as $capability => $grant){
        $roleInternet->addCapability($capability, $grant);
    }
}

//add capability theme_images
$roleInternet->addCapability('theme_images', true);

$roleAdministrator = Role::find('administrator');
foreach($roleAdministrator->getCapabilities() as $capability => $grant){
    if(substr($capability, -6) != "_users") {
        $roleInternet->addCapability($capability, $grant);
    }
}

$roleInternet->save();

/**
 * Also add the custom capabilities to WP administrator
 */
foreach($roleInternet->getCapabilities() as $capability => $grant){
    if(substr($capability, -6) != "_users") {
        $roleAdministrator->addCapability($capability, $grant);
    }
}

$roleAdministrator->save();