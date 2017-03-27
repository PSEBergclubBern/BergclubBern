<?php
$functionaryRoles = [
    'leiter' => [
        'name' => 'Leiter',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
            'touren_edit_posts' => true,
            'touren_edit_published_posts' => true,
        ],
    ],
    'leiter_jugend' => [
        'name' => 'Leiter Jugend',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_jugend_read' => true,
            'rueckmeldungen_jugend_edit' => true,
            'touren_jugend_edit_posts' => true,
            'touren_jugend_edit_published_posts' => true,
        ],
    ],
    'tourenchef' => [
        'name' => 'Tourenchef',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_edit_others' => true,
            'edit_posts' => true,
            'touren_edit_posts_others' => true,
            'touren_edit_published_posts_others' => true,
            'touren_publish_posts' => true,
        ],
    ],
    'tourenchef_jugend' => [
        'name' => 'Tourenchef Jugend',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'rueckmeldungen_jugend_edit_others' => true,
            'edit_posts' => true,
            'touren_jugend_edit_posts_others' => true,
            'touren_jugend_edit_published_posts_others' => true,
            'touren_jugend_publish_posts' => true,
        ],
    ],
    'redaktion' => [
        'name' => 'Redaktion',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'edit_others_posts' => true,
            'publish_posts' => true,
            'edit_published_posts' => true,
            'edit_others_pages' => true,
            'touren_edit_posts_others' => true,
            'touren_edit_published_posts_others' => true,
            'touren_jugend_edit_posts_others' => true,
            'touren_jugend_edit_published_posts_others' => true,
        ],
    ],
    'sekretariat' => [
        'name' => 'Sekretariat',
        'capabilities' => [
            'read' => true,
            'adressen_read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_edit_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'rueckmeldungen_jugend_edit_others' => true,
        ],
    ],
    'mutationen' => [
        'name' => 'Mutationen',
        'capabilities' => [
            'read' => true,
            'adressen_read' => true,
            'adressen_edit' => true,
        ],
    ],
    'kasse' => [
        'name' => 'Kasse',
        'capabilities' => [
            'read' => true,
            'adressen_read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
        ],
    ],
    'praesident' => [
        'name' => 'Präsident',
        'capabilities' => [
            'read' => true,
            'adressen_read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
        ],
    ],
    'internet' => [
        'name' => 'Internet',
        'capabilities' => [
            'read' => true,
            'adressen_read' => true,
            'adressen_edit' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_edit_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'rueckmeldungen_jugend_edit_others' => true,
            'edit_others_posts' => true,
            'publish_posts' => true,
            'edit_published_posts' => true,
            'edit_others_pages' => true,
            'touren_edit_posts_others' => true,
            'touren_edit_published_posts_others' => true,
            'touren_jugend_edit_posts_others' => true,
            'touren_jugend_edit_published_posts_others' => true,
            'touren_publish_posts' => true,
            'touren_jugend_publish_posts' => true,
            'stammdaten_read' => true,
            'stammdaten_edit' => true,
        ],
    ],
    'materialchef' => [
        'name' => 'Materialchef',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'materialchef_jugend' => [
        'name' => 'Materialchef Jugend',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'js_coach' => [
        'name' => 'J&S-Coach',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'versand' => [
        'name' => 'Versand',
        'capabilities' => [
            'read' => false,
        ],
    ],
];