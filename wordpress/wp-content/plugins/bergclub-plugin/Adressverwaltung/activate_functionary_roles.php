<?php
$functionaryRoles = [
    'bcb_leiter' => [
        'name' => 'Leiter/in',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'create_tour' => true,
            'publish_tour' => false,
            'edit_tour' => true,
            'edit_others_tour' => false,
            'delete_tour' => true,
            'delete_others_tour' => false,
            'read_private_tour' => false,
            'read_tour' => true,

            'create_tourenbericht' => true,
            'publish_tourenbericht' => false,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => false,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => false,
            'read_private_tourenbericht' => false,
            'read_tourenbericht' => true,

            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
        ],
    ],

    'bcb_leiter_jugend' => [
        'name' => 'Leiter/in Jugend',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'create_tour' => true,
            'publish_tour' => false,
            'edit_tour' => true,
            'edit_others_tour' => false,
            'delete_tour' => true,
            'delete_others_tour' => false,
            'read_private_tour' => false,
            'read_tour' => true,

            'create_tourenbericht' => true,
            'publish_tourenbericht' => false,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => false,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => false,
            'read_private_tourenbericht' => false,
            'read_tourenbericht' => true,

            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
        ],
    ],
    'bcb_tourenchef' => [
        'name' => 'Tourenchef/in',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'create_tour' => true,
            'publish_tour' => true,
            'edit_tour' => true,
            'edit_others_tour' => true,
            'delete_tour' => true,
            'delete_others_tour' => true,
            'read_private_tour' => true,
            'read_tour' => true,

            'create_tourenbericht' => true,
            'publish_tourenbericht' => true,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => true,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => true,
            'read_private_tourenbericht' => true,
            'read_tourenbericht' => true,

            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
            'rueckmeldungen_edit_others' => true,
            'rueckmeldungen_publish' => true,

            'export' => true,
            'export_touren' => true,
        ],
    ],
    'bcb_tourenchef_jugend' => [
        'name' => 'Tourenchef/in Jugend',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'create_tour' => true,
            'publish_tour' => true,
            'edit_tour' => true,
            'edit_others_tour' => true,
            'delete_tour' => true,
            'delete_others_tour' => true,
            'read_private_tour' => true,
            'read_tour' => true,

            'create_tourenbericht' => true,
            'publish_tourenbericht' => true,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => true,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => true,
            'read_private_tourenbericht' => true,
            'read_tourenbericht' => true,

            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
            'rueckmeldungen_edit_others' => true,
            'rueckmeldungen_publish' => true,

            'export' => true,
            'export_touren' => true,
        ],
    ],
    'bcb_redaktion' => [
        'name' => 'Redaktion',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'delete_others_posts' => true,
            'delete_published_posts' => true,
            'delete_private_posts' => true,
            'edit_private_posts' => true,
            'read_private_posts' => true,
            'read_post' => true,

            'create_tour' => true,
            'publish_tour' => true,
            'edit_tour' => true,
            'edit_others_tour' => true,
            'delete_tour' => true,
            'delete_others_tour' => true,
            'read_private_tour' => true,
            'read_tour' => true,

            'create_tourenbericht' => true,
            'publish_tourenbericht' => true,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => true,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => true,
            'read_private_tourenbericht' => true,
            'read_tourenbericht' => true,

            'rueckmeldungen_read' => true,
            'rueckmeldungen_edit' => true,
            'rueckmeldungen_edit_others' => true,
            'rueckmeldungen_publish' => true,

            'export' => true,
            'export_touren' => true,
            'export_druck' => true,

            'adressen_read' => true,
        ],
    ],
    'bcb_sekretariat' => [
        'name' => 'Sekretariat',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'adressen_read' => true,

            'rueckmeldungen_read' => true,
        ],
    ],
    'bcb_mutationen' => [
        'name' => 'Mutationen',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'adressen_read' => true,
            'adressen_edit' => true,

            'export' => true,
            'export_adressen' => true,
        ],
    ],
    'bcb_kasse' => [
        'name' => 'Kasse',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'adressen_read' => true,
            'export' => true,
            'export_adressen' => true,
            'rueckmeldungen_read' => true,
            'rueckmeldungen_pay' => true,
        ],
    ],
    'bcb_praesident' => [
        'name' => 'Präsident/in',
        'capabilities' => [
            'read' => true,

            'edit_posts' => true,
            'delete_posts' => true,
            'edit_others_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'delete_others_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'read_post' => true,

            'publish_tour' => false,
            'edit_tour' => true,
            'edit_others_tour' => false,
            'delete_tour' => true,
            'delete_others_tour' => false,
            'read_private_tour' => false,
            'read_tour' => true,

            'publish_tourenbericht' => false,
            'edit_tourenbericht' => true,
            'edit_others_tourenbericht' => false,
            'delete_tourenbericht' => true,
            'delete_others_tourenbericht' => false,
            'read_private_tourenbericht' => false,
            'read_tourenbericht' => true,

            'adressen_read' => true,
            'rueckmeldungen_read' => true,

            'export' => true,
            'export_adressen' => true,
            'export_touren' => true,
            'export_druck' => true,
        ],
    ],
    'bcb_materialchef' => [
        'name' => 'Materialchef/in',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'bcb_materialchef_jugend' => [
        'name' => 'Materialchef/in Jugend',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'bcb_js_coach' => [
        'name' => 'J&S-Coach',
        'capabilities' => [
            'read' => false,
        ],
    ],
    'bcb_versand' => [
        'name' => 'Versand',
        'capabilities' => [
            'read' => false,
        ],
    ],
];