<?php
$functionaryRoles = [
    'leiter' => [
        'name' => 'Leiter',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
            'edit_touren' => true,
            'edit_published_touren' => true,
        ],
    ],
    'leiter_jugend' => [
        'name' => 'Leiter Jugend',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_jugend_read' => true,
            'rueckmeldungen_jugend_edit' => true,
            'edit_touren_jugend' => true,
            'edit_published_touren_jugend' => true,
        ],
    ],
    'tourenchef' => [
        'name' => 'Tourenchef',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_edit_others' => true,
            'edit_posts' => true,
            'edit_touren' => true,
            'edit_others_touren' => true,
            'publish_touren' => true,
        ],
    ],
    'tourenchef_jugend' => [
        'name' => 'Tourenchef Jugend',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'rueckmeldungen_jugend_edit_others' => true,
            'edit_posts' => true,
            'edit_touren_jugend' => true,
            'edit_others_touren_jugend' => true,
            'publish_touren_jugend' => true,
        ],
    ],
    'redaktion' => [
        'name' => 'Redaktion',
        'capabilities' => [
            'read' => true,
            'rueckmeldungen_read_others' => true,
            'rueckmeldungen_jugend_read_others' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'publish_posts' => true,
            'edit_published_posts' => true,
            'edit_pages' => true,
            'edit_others_pages' => true,
            'edit_touren' => true,
            'edit_others_touren' => true,
            'edit_published_touren' => true,
            'edit_others_touren_jugend' => true,
            'edit_published_touren_jugend' => true,
            'edit_tourenberichte' => true,
            'edit_others_tourenberichte' => true,
            'edit_published_tourenberichte' => true,
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