<?php
use BergclubPlugin\MVC\Models\User;
use BergclubPlugin\MVC\Models\Role;

function createFakeUser(Role $role, $createCompany = false, $login = null){
    $faker = Faker\Factory::create();

    $company = '';
    if($createCompany){
        $company = $faker->company;
    }

    $user = new User([
        'user_login' => $login,
        'user_pass' => $login,
        'company' => $company,
        'gender' => 'M',
        'first_name' => $faker->firstNameMale,
        'last_name' => $faker->lastName,
        'street' => $faker->streetName . ' ' . $faker->buildingNumber,
        'zip' => $faker->postcode,
        'location' => $faker->city,
        'phone_private' => $faker->phoneNumber,
        'phone_work' => $faker->phoneNumber,
        'phone_mobile' => $faker->phoneNumber,
        'email' => $faker->email,
        'birthdate' => $faker->date('d.m.Y', '1997-01-01'),
    ]);
    $user->addRole($role);
    $user->save();
    return $user;
}

function createMultipleFakeUser(Role $role, $times, $createCompany = false){
    for($i=0; $i<$times; $i++) {
        createFakeUser($role, $createCompany);
    }
}


$role = new Role(Role::TYPE_ADDRESS, 'institution', 'Institution');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 3, true);

$role = new Role(Role::TYPE_ADDRESS, 'inserent', 'Inserent');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 3, true);

$role = new Role(Role::TYPE_ADDRESS, 'interessent', 'Interessent');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 3);

$role = new Role(Role::TYPE_ADDRESS, 'interessent_jugend', 'Interessent Jugend');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 3);

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied', 'Aktivmitglied');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied_jugend', 'Aktivmitglied Jugend');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'ehrenmitglied', 'Ehrenmitglied');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'ehemalig', 'Ehemalig');
$role->addCapability('read', false);
$role->save();

createMultipleFakeUser($role, 3);

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

/**
 * Create a user with login for each functionary role that has capabilities. Username and password will be the role slug
 * (without 'bcb_' prefix)
 */
$addressRole = Role::find('aktivmitglied');

$roles = Role::findByType(Role::TYPE_FUNCTIONARY);
foreach($roles as $role){
    /* @var Role $role */
    $capabilities = $role->getCapabilities();
    if(array_key_exists('read', $capabilities) && $capabilities['read']) {
        $user = createFakeUser($addressRole, false, \BergclubPlugin\MVC\Helpers::ensureKeyHasNoPrefix($role->getKey()));
        $user->addRole($role);
        $user->save();
    }
}
