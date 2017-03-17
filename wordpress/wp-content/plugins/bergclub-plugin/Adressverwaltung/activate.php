<?php
use BergclubPlugin\MVC\Models\User;

$user = new User([
    'first_name' => 'Dominik',
    'last_name' => 'Fankhauser',
    'email' => 'domi.92@hotmail.com',
    'phone_mobile' => '077 405 78 24',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Kevin',
    'last_name' => 'Studer',
    'email' => 'kreemer@me.com',
    'phone_mobile' => '079 207 73 87',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Marcel',
    'last_name' => 'Chavez',
    'email' => 'chavez.marcel@gmail.com',
    'phone_mobile' => '079 245 08 68',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Marco',
    'last_name' => 'Indermühle',
    'email' => 'indy26@bluewin.ch',
    'phone_mobile' => '079 534 13 57',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Mathias',
    'last_name' => 'Stocker',
    'email' => 'mathias.stocker@students.unibe.ch',
    'street' => 'Dorfstrasse 67',
    'zip' => 'PLZ',
    'location' => 'Ort',
    'phone_mobile' => '079 659 60 95',
    'phone_private' => '031 511 23 43',
    'phone_work' => '031 511 23 03',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Sven',
    'last_name' => 'Kellenberger',
    'email' => 'sven.kellenberger@kellenberger.tv',
    'phone_mobile' => '078 914 65 14',
    'type' => 'Mitglied BCB',
]);

$user->save();

$user = new User([
    'first_name' => 'Timm',
    'last_name' => 'Gross',
    'email' => 'timm.gross@students.unibe.ch',
    'phone_mobile' => '077 435 34 83',
    'type' => 'Mitglied BCB',
]);

$user->save();