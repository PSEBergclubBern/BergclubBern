<?php
$mitgliederBeitraege = [
    'bcb' => [
        'name' => 'Mitgliederbeitrag BCB',
        'amount' => 50,
    ],
    'jugend' => [
        'name' => 'Mitgliederbeitrag Jugend',
        'amount' => 20,
    ],
    'ehepaar' => [
        'name' => 'Mitgliederbeitrag Ehepaar',
        'amount' => 50,
    ],
];

add_option('bcb_mitgliederbeitraege', $mitgliederBeitraege, '', 'no');

$keyBergtour = sanitize_title_with_dashes('bcb_bergtour');
$keySkitour = sanitize_title_with_dashes('bcb_skitour');
$keyWanderung = sanitize_title_with_dashes('bcb_wanderung');

$tourenarten = [
    $keyBergtour => 'Bergtour',
    $keySkitour => 'Skitour',
    $keyWanderung => 'Wanderung'
];

$schwierigkeitenBergtour = [
    'Leicht',
    'Mittel',
    'Schwer'
];

$schwierigkeitenSkitour = [
    'Leicht',
    'Mittel',
    'Schwer'
];

$schwierigkeitenWandern = [
    'Leicht',
    'Mittel',
    'Schwer'
];

add_option('bcb_tourenarten', $tourenarten, '', 'no');
add_option($keyBergtour, $schwierigkeitenBergtour, '','no');
add_option($keySkitour, $schwierigkeitenSkitour, '', 'no');
add_option($keyWanderung, $schwierigkeitenWandern, '', 'no');