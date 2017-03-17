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