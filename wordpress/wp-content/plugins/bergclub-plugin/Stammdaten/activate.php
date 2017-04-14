<?php
use BergclubPlugin\MVC\Models\Option;

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

Option::set('mitgliederbeitraege', $mitgliederBeitraege);

$tourenarten = [
    'bcb_bergtour' => 'Bergtour',
    'bcb_skitour' => 'Skitour',
    'bcb_langlauf' => 'Langlauf',
    'bcb_klettertraining' => 'Klettertraining',
    'bcb_velotour' => 'Velotour',
    'bcb_hochtour' => 'Hochtour',
    'bcb_pistenfahren' => 'Pistenfahren',
    'bcb_schneeschuhwanderung' => 'Schneeschuhwanderung',
    'bcb_klettertour' => 'Klettertour',
    'bcb_diverses' => 'Diverses',
    'bcb_wanderung' => 'Wanderung',
    'bcb_klettersteig' => 'Klettersteig',
    'bcb_hoehlentour' => 'Höhlentour',
    'bcb_abendwanderung' => 'Abendwanderung',
];

$schwierigkeiten = [
    '',
    'T1 (Wanderung)',
    'T2 (Bergwandern)',
    'T3 (Ansp. Bergwandern)',
    'T4 (Alpinwanderung)',
    'T5 (Ansp. Alpinwandern)',
    'T6 (Schw. Alpinwandern)',
    'K1 (sehr einfach)',
    'K2 (einfach)',
    'K3 (mässig schwierig)',
    'K4 (schwierig)',
    'K5 (sehr schwierig)',
    'K6 (äusserst schwierig)',
    'L (leicht)',
    'WS (wenig schwierig)',
    'ZS (ziemlich schwierig)',
    'S (schwierig)',
];

Option::set('tourenarten', $tourenarten);

foreach($tourenarten as $key => $tourenart){
    Option::set($key, $schwierigkeiten);
}