<?php
use BergclubPlugin\MVC\Models\User;
use BergclubPlugin\MVC\Models\Role;

function createFakeUser(Role $role, $times, $createCompany = false){
    $faker = Faker\Factory::create();

    $company = '';

    for($i=0; $i<$times; $i++) {
        if($createCompany){
            $company = $faker->company;
        }

        $user = new User([
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
    }
}


$role = new Role(Role::TYPE_ADDRESS, 'institution', 'Institution');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 3, true);

$role = new Role(Role::TYPE_ADDRESS, 'inserent', 'Inserent');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 3, true);

$role = new Role(Role::TYPE_ADDRESS, 'interessent', 'Interessent');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 3);

$role = new Role(Role::TYPE_ADDRESS, 'interessent_jugend', 'Interessent Jugend');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 3);

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied', 'Aktivmitglied');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'aktivmitglied_jugend', 'Aktivmitglied Jugend');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'ehrenmitglied', 'Ehrenmitglied');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 10);

$role = new Role(Role::TYPE_ADDRESS, 'ehemalig', 'Ehemalig');
$role->addCapability('read', false);
$role->save();

createFakeUser($role, 3);


