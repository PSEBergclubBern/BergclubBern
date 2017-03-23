<?php
use BergclubPlugin\MVC\Models\User;

/*
 * Creation of WP Option `bcb_roles`
 * This option will hold a list of all custom roles that are added to WP by this plugin.
 * The key `address` will hold the roles key and name for address type roles.
 * The key `functionary` will hold the roles key and name for functionary roles.
 */
$customRoles = [
  'address' => [
      'institution' => 'Institution',
      'inserent' => 'Inserent',
      'interessent' => 'Interessent',
      'interessent_jugend' => 'Interessent Jugend',
      'aktivmitglied' => 'Aktivmitglied',
      'aktivmtigleid_jugend' => 'Aktivmitglied Jugend',
      'ehrenmitglied' => 'Ehrenmitglied',
      'ehemalig' => 'Ehemalig',
  ],
  'functionary' => [
      'leiter' => 'Leiter',
      'leiter_jugend' => 'Leiter Jugend',
      'tourenchef' => 'Tourenchef',
      'tourenchef_jugend' => 'Tourenchef Jugend',
      'redaktion' => 'Redaktion',
      'sekretariat' => 'Sekretatiat',
      'mutationen' => 'Mutationen',
      'kasse' => 'Kasse',
      'praesident' => 'Präsident',
      'internet' => 'Internet',
      'materialchef' => 'Materialchef',
      'materialchef_jugend' => 'Materialchef Jugend',
      'js_coach' => 'J&S-Coach',
      'versand' => 'Versand',
  ],
];

$option = new \BergclubPlugin\MVC\Models\Option('roles', $customRoles);
$option->save();

/*
 * Creation of WP Option `bcb_capabilities`.
 * This option will hold a list of all capabilities assigned to the custom roles defined in `bcb_roles`.
 */
$capabilities = [
    'institution' => [
        'read' => false,
    ],
    'inserent' => [
        'read' => false,
    ],
    'interessent' => [
        'read' => false,
    ],
    'interessent_jugend' => [
        'read' => false,
    ],
    'aktivmitglied' => [
        'read' => false,
    ],
    'aktivmtigleid_jugend' => [
        'read' => false,
    ],
    'ehrenmitglied' => [
        'read' => false,
    ],
    'ehemalig' => [
        'read' => false,
    ],
    'leiter' => [
        'read' => true,
        'rueckmeldungen_read' => true,
        'rueckmeldungen_edit' => true,
        'touren_edit_posts' => true,
        'touren_edit_published_posts' => true,
    ],
    'leiter_jugend' => [
        'read' => true,
        'rueckmeldungen_jugend_read' => true,
        'rueckmeldungen_jugend_edit' => true,
        'touren_jugend_edit_posts' => true,
        'touren_jugend_edit_published_posts' => true,
    ],
    'tourenchef' => [
        'read' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_edit_others' => true,
        'edit_posts' => true,
        'touren_edit_posts_others' => true,
        'touren_edit_published_posts_others' => true,
        'touren_publish_posts' => true,
    ],
    'tourenchef_jugend' => [
        'read' => true,
        'rueckmeldungen_jugend_read_others' => true,
        'rueckmeldungen_jugend_edit_others' => true,
        'edit_posts' => true,
        'touren_jugend_edit_posts_others' => true,
        'touren_jugend_edit_published_posts_others' => true,
        'touren_jugend_publish_posts' => true,
    ],
    'redaktion' => [
        'read' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_jugend_read_others' => true,
        'edit_others_posts' => true,
        'publish_posts' => true,
        'edit_published_posts' => true,
        'edit_otherss_pages' => true,
        'touren_edit_posts_others' => true,
        'touren_edit_published_posts_others' => true,
        'touren_jugend_edit_posts_others' => true,
        'touren_jugend_edit_published_posts_others' => true,
    ],
    'sekretariat' => [
        'read' => true,
        'adressen_read_others' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_edit_others' => true,
        'rueckmeldungen_jugend_read_others' => true,
        'rueckmeldungen_jugend_edit_others' => true,
    ],
    'mutationen' => [
        'read' => true,
        'adressen_read_others' => true,
        'adressen_edit_others' => true,
    ],
    'kasse' => [
        'read' => true,
        'adressen_read_others' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_jugend_read_others' => true,
    ],
    'praesident' => [
        'read' => true,
        'adressen_read_others' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_jugend_read_others' => true,
    ],
    'internet' => [
        'read' => true,
        'adressen_read_others' => true,
        'adressen_edit_others' => true,
        'rueckmeldungen_read_others' => true,
        'rueckmeldungen_edit_others' => true,
        'rueckmeldungen_jugend_read_others' => true,
        'rueckmeldungen_jugend_edit_others' => true,
        'edit_others_posts' => true,
        'publish_posts' => true,
        'edit_published_posts' => true,
        'edit_otherss_pages' => true,
        'touren_edit_posts_others' => true,
        'touren_edit_published_posts_others' => true,
        'touren_jugend_edit_posts_others' => true,
        'touren_jugend_edit_published_posts_others' => true,
        'touren_publish_posts' => true,
        'touren_jugend_publish_posts' => true,
        'stammdaten_edit' => true,
    ],
    'materialchef' => [
        'read' => false,
    ],
    'materialchef_jugend' => [
        'read' => false,
    ],
    'js_coach' => [
        'read' => false,
    ],
    'versand' => [
        'read' => false,
    ],
];

/*
 * The custom role `internet` will be the assigned to the administrator of Bergclub Bern, so we add all capabilities of
 * the default `administrator` role except for the capabilities needed for user management, because user management will
 * be done in "Adressverwaltung".
 */
$roleAdministrator = get_role('administrator');
foreach($roleAdministrator->capabilities as $capability => $grant){
    if(substr($capability, -6) != "_users") {
        $capabilities['internet'][$capability] = $grant;
    }
}

$option = new \BergclubPlugin\MVC\Models\Option('capabilities', $capabilities);
$option->save();

/*
 * Creation of the WP Role entries from our custom roles list.
 */
foreach($customRoles as $roles){
    foreach($roles as $key => $name){
        $role = new \BergclubPlugin\MVC\Models\Role($key, $name);
        $role->setCapabilities($capabilities[$key]);
        $role->save();
    }
}


/*
 * Creation of dummy user data, will be replaced later when user import is finished.
 */
$user = new User([
    'gender' => 'M',
    'first_name' => 'Dominik',
    'last_name' => 'Fankhauser',
    'email' => 'domi.92@hotmail.com',
    'phone_mobile' => '077 405 78 24',
    'type' => 'Mitglied BCB',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Kevin',
    'last_name' => 'Studer',
    'email' => 'kreemer@me.com',
    'phone_mobile' => '079 207 73 87',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Marcel',
    'last_name' => 'Chavez',
    'email' => 'chavez.marcel@gmail.com',
    'phone_mobile' => '079 245 08 68',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Marco',
    'last_name' => 'Indermühle',
    'email' => 'indy26@bluewin.ch',
    'phone_mobile' => '079 534 13 57',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Mathias',
    'last_name' => 'Stocker',
    'email' => 'mathias.stocker@students.unibe.ch',
    'street' => 'Dorfstrasse 67',
    'zip' => '3073',
    'location' => 'Gümligen',
    'phone_mobile' => '079 659 60 95',
    'phone_private' => '031 511 23 43',
    'phone_work' => '031 511 23 03',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Sven',
    'last_name' => 'Kellenberger',
    'email' => 'sven.kellenberger@kellenberger.tv',
    'phone_mobile' => '078 914 65 14',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

$user = new User([
    'gender' => 'M',
    'first_name' => 'Timm',
    'last_name' => 'Gross',
    'email' => 'timm.gross@students.unibe.ch',
    'phone_mobile' => '077 435 34 83',
    'roles' => [
        'aktivmitglied',
    ]
]);

$user->save();

/*
 * Ensure that all users with role `administrator` hav the capabilities of custom admin role `internet`.
 */

$roleAdministrator = get_role('administrator');
foreach($capabilities['internet'] as $capability => $grant) {
    $roleAdministrator->add_cap(\BergclubPlugin\MVC\Helpers::ensureKeyHasPrefix($capability), $grant);
}

