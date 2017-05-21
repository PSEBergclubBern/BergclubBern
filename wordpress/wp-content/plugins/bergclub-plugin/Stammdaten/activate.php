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
    'bcb_abendwanderung' => 'Abendwanderung',
    'bcb_bergtour' => 'Bergtour',
    'bcb_hochtour' => 'Hochtour',
    'bcb_hoehlentour' => 'Höhlentour',
    'bcb_klettersteig' => 'Klettersteig',
    'bcb_klettertour' => 'Klettertour',
    'bcb_klettertraining' => 'Klettertraining',
    'bcb_langlauf' => 'Langlauf',
    'bcb_pistenfahren' => 'Pistenfahren',
    'bcb_schneeschuhwanderung' => 'Schneeschuhw.',
    'bcb_skitour' => 'Skitour',
    'bcb_velotour' => 'Velotour',
    'bcb_wanderung' => 'Wanderung',
    'bcb_diverses' => 'Diverses',
];

/**
 *  create the default "Schwierigkeiten" for the "Tourenarten"
 */
$schwierigkeiten = [
    'bcb_abendwanderung' => [],
    'bcb_bergtour' => [
        'T2 - Bergwandern',
        'T3 - anspruchsv. Bergwandern',
        'T4 - Alpinwandern',
        'T5 - anspruchsv. Alpinwandern',
        'T6 - schwieriges Alpinwandern',
    ],
    'bcb_hochtour' => [
        'L - leicht',
        'WS - wenig schwierig',
        'ZS - ziemlich schwierig',
        'S - schwierig',
        'SS - sehr schwierig',
        'AS - ausserord. schwierig',
        'EX - extrem schwierig',
    ],
    'bcb_hoehlentour' => [],
    'bcb_klettersteig' => [],
    'bcb_klettertour' => [
        'I - Geringe Schw.',
        'II - Mässige Schw.',
        'III - Mittlere Schw.',
        'IV - Grosse Schw.',
        'V - Sehr grosse Schw.',
        'VI - Überaus grosse Schw.',
        'VII - Aussergew. Schw.',
    ],
    'bcb_klettertraining' => [],
    'bcb_langlauf' => [],
    'bcb_pistenfahren' => [],
    'bcb_schneeschuhwanderung' => [
        'WT 1 - Leicht',
        'WT 2 - Normal',
        'WT 3 - Anspruchsvoll',
        'WT 4 - Schwierig',
        'WT 5 - Alpine Tour',
        'WT 6 - Anspruchsv. Alpine Tour',
    ],
    'bcb_skitour' => [
        'L - leicht',
        'WS - wenig schwierig',
        'ZS - ziemlich schwierig',
        'S - schwierig',
        'SS - sehr schwierig',
        'AS - ausserord. schwierig',
        'EX - extrem schwierig',
    ],
    'bcb_velotour' => [],
    'bcb_wanderung' => [],
    'bcb_diverses' => [],
];

/**
 *  add the "Tourenarten" to WP option table and add each "Schwierigkeit"
 *  to each "Tourenart"
 */
Option::set('tourenarten', $tourenarten);

foreach ($schwierigkeiten as $key => $arr) {
    Option::set($key, $arr);
}