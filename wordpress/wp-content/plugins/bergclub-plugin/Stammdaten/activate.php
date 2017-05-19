<?php
use BergclubPlugin\MVC\Models\Option;

/**
 *  create the three different "Mitgliederbeiträge" with their default values
 */
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

// add "Mitgliederbeiträge" to WP option table
Option::set('mitgliederbeitraege', $mitgliederBeitraege);

/**
 *  create the default "Tourenarten"
 */
$tourenarten = [
    'bcb_bergtour' => 'Bergtour',
    'bcb_skitour' => 'Skitour',
    'bcb_langlauf' => 'Langlauf',
    'bcb_klettertraining' => 'Klettertraining',
    'bcb_velotour' => 'Velotour',
    'bcb_hochtour' => 'Hochtour',
    'bcb_pistenfahren' => 'Pistenfahren',
    'bcb_schneeschuhwanderung' => 'Schneeschuhw.',
    'bcb_klettertour' => 'Klettertour',
    'bcb_diverses' => 'Diverses',
    'bcb_wanderung' => 'Wanderung',
    'bcb_klettersteig' => 'Klettersteig',
    'bcb_hoehlentour' => 'Höhlentour',
    'bcb_abendwanderung' => 'Abendwanderung',
];

/**
 *  create the default "Schwierigkeiten" for the "Tourenarten"
 */
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

/**
 *  add the "Tourenarten" to WP option table and add each "Schwierigkeit"
 *  to each "Tourenart" as default
 */
Option::set('tourenarten', $tourenarten);

foreach ($tourenarten as $key => $tourenart) {
    Option::set($key, $schwierigkeiten);
}