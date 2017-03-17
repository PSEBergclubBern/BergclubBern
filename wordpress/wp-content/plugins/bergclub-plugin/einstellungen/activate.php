<?php
//Hinweis: Ich würde die fix programmieren, also nicht löschbar machen und kein hinzufügen erlauben.
//Es ist immer besser einen "key" zu haben, der entweder numerisch ist oder aus ascii und ohne Leerzeichen besteht

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