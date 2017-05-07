<?php


//mock wp functions and classes for the Models namespace
namespace BergclubPlugin\MVC\Models {
    global $wpUsers;
    global $wpUsersMeta;
    global $wpRoles;

    function get_user_by($field, $value)
    {
        global $wpUsers;
        if($field == 'ID') {
            if (isset($wpUsers[$value])) {
                return $wpUsers[$value];
            }
        }elseif($field == 'login'){
            foreach($wpUsers as $wpUser){
                if($wpUser->data['user_login'] == $value){
                    return $wpUser;
                }
            }
        }

        return null;
    }

    function get_users($args = null){
        global $wpUsers;
        if(!$args) {
            return $wpUsers;
        }elseif(isset($args['role'])){
            $result = [];
            foreach($wpUsers as $wpUser){
                if(in_array($args['role'], $wpUser->roles)){
                    $result[] = $wpUser;
                }
            }
            return $result;
        }

        return [];
    }

    function get_user_meta($id){
        global $wpUsersMeta;
        if(isset($wpUsersMeta[$id])){
            return $wpUsersMeta[$id];
        }

        return null;
    }

    function get_current_user_id(){
        return 1;
    }

    function wp_insert_user($args){
        global $wpUsers;
        global $wpUsersMeta;

        $lastUser = end($wpUsers);
        $id = $lastUser->ID + 1;
        $tm = date("U");

        $wpUser = new \stdClass();
        $wpUser->data = [
            'ID' => $id,
            'user_login' => $args['user_login'],
            'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
            'user_nicename' => $args['user_login'],
            'user_email' => $args['user_email'],
            'user_registered' => date('Y-m-d H:i:s', $tm),
            'user_activation_key' => null,
            'user_status' => 0,
            'display_name' => $args['user_login'],
        ];
        $wpUser->ID = $id;
        $wpUser->caps = [];
        $wpUser->cap_key = 'wp_capabilities';
        $wpUser->roles = [];
        $wpUser->allcaps = ['read' => null];

        $wpUsers[$id] = $wpUser;

        $wpUsersMeta[$id] = [
            'nickname' => $args['user_login'],
            'description' => [null],
            'rich_editing' => [true],
            'comment_shortcuts' => [false],
            'admin_color' => ['fresh'],
            'use_ssl' => [0],
            'show_admin_bar_front' => [true],
            'locale' => [null],
            'wp_capabilities' => [serialize([])],
            'wp_user_level' => [0],
            'dismissed_wp_pointers' => [null],
        ];
    }

    function wp_update_user($args, $data){
        global $wpUsers;
        $id = $args['ID'];
        foreach($data as $key => $value) {
            $wpUsers[$id]->data[$key] = $value;
        }
    }

    function update_user_meta($id, $field, $value){

    }

    function sanitize_email($value){
        return $value;
    }

    function sanitize_text_field($value){
        return $value;
    }

    class Role {
        const TYPE_SYSTEM = 'system';
        const TYPE_ADDRESS = 'address';
        const TYPE_FUNCTIONARY = 'functionary';

        private $key;

        public static $roles = [
            'bcb_aktivmitglied' => [
                'type' => 'address',
                'name' => 'Aktivmitglied'
            ],
            'bcb_ehemalig' => [
                'type' => 'address',
                'name' => 'Ehemalig',
            ],
            'bcb_leiter' => [
                'type' => 'functionary',
                'name' => 'Leiter'
            ],
            'bcb_tourenchef' => [
                'type' => 'functionary',
                'name' => 'Tourenchef'
            ],
            'bcb_leiter_jugend' => [
                'type' => 'functionary',
                'name' => 'Leiter Jugend'
            ],
            'bcb_tourenchef_jugend' => [
                'type' => 'functionary',
                'name' => 'Tourenchef Jugend'
            ],
            'bcb_materialchef' => [
                'type' => 'functionary',
                'name' => 'Materialchef'
            ],
            'bcb_inserent' => [
                'type' => 'address',
                'name' => 'Inserent'
            ],
            'administrator' => [
                'type' => 'system',
                'name' => 'Administrator'
            ],
        ];

        public function __construct($key){
            $this->key = $key;
        }

        public static function find($key){
            if(isset(self::$roles[$key])) {
                return new Role($key);
            }

            return null;
        }

        public function getType(){
            return self::$roles[$this->key]['type'];
        }

        public function getKey(){
            return $this->key;
        }

        public function getName(){
            return self::$roles[$this->key]['name'];
        }
    }
}

//mock wp_mail
namespace BergclubPlugin\MVC {
    global $wpMail;

    function wp_mail($to, $subject, $message){
        global $wpMail;
        $wpMail = ['to' => $to, 'subject' => $subject, 'message' => $message];
    }
}

namespace BergclubPlugin\Tests\MVC\Models {
    global $wpUsers;
    global $wpUsersMeta;

    use BergclubPlugin\MVC\Models\User;
    use PHPUnit\Framework\TestCase;

    class UserTest extends TestCase
    {
        /**
         * @Before
         */
        public function setUp()
        {
            global $wpUsers;
            global $wpUsersMeta;

            $tm = date("U");
            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 1,
                'user_login' => 'usertest',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest',
            ];
            $wpUser->ID = 1;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1];

            $wpUsers[1] = $wpUser;

            $wpUsersMeta[1] = [
                'nickname' => ['usertest'],
                'first_name' => ['Test'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:17:"bcb_aktivmitglied";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:1:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];



            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 2,
                'user_login' => 'usertest2',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest2',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest2',
            ];
            $wpUser->ID = 2;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1];

            $wpUsers[2] = $wpUser;

            $wpUsersMeta[2] = [
                'nickname' => ['usertest2'],
                'first_name' => ['Test 2'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:17:"bcb_aktivmitglied";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test2@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [3],
                'main_address' => [true],
                'mail_sent' => [null],
                'history' => ['a:1:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 3,
                'user_login' => 'usertest3',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest3',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest3',
            ];
            $wpUser->ID = 3;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1];

            $wpUsers[3] = $wpUser;

            $wpUsersMeta[3] = [
                'nickname' => ['usertest3'],
                'first_name' => ['Test 3'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:17:"bcb_aktivmitglied";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test3@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [2],
                'main_address' => [false],
                'mail_sent' => [null],
                'history' => ['a:1:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 4,
                'user_login' => 'usertest4',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest4',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest4',
            ];
            $wpUser->ID = 4;
            $wpUser->caps = [
                'bcb_inserent' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_inserent'];
            $wpUser->allcaps = ['read' => null, 'bcb_inserent' => 1];

            $wpUsers[4] = $wpUser;

            $wpUsersMeta[4] = [
                'nickname' => ['usertest4'],
                'first_name' => ['Test 4'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:12:"bcb_inserent";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => ['Test AG'],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test4@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [null],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:1:{s:12:"bcb_inserent";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 5,
                'user_login' => 'usertest5',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest5',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest5',
            ];
            $wpUser->ID = 5;
            $wpUser->caps = [
                'bcb_ehemalig' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_ehemalig'];
            $wpUser->allcaps = ['read' => null, 'bcb_ehemalig' => 1];

            $wpUsers[5] = $wpUser;

            $wpUsersMeta[5] = [
                'nickname' => ['usertest5'],
                'first_name' => ['Test 5'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:12:"bcb_ehemalig";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [2],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test5@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [6],
                'main_address' => [true],
                'mail_sent' => [null],
                'history' => ['a:1:{s:12:"bcb_ehemalig";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 6,
                'user_login' => 'usertest6',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest6',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest6',
            ];
            $wpUser->ID = 6;
            $wpUser->caps = [
                'bcb_ehemalig' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_ehemalig'];
            $wpUser->allcaps = ['read' => null, 'bcb_ehemalig' => 1];

            $wpUsers[6] = $wpUser;

            $wpUsersMeta[6] = [
                'nickname' => ['usertest6'],
                'first_name' => ['Test 6'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:12:"bcb_ehemalig";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [1],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test6@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [5],
                'main_address' => [false],
                'mail_sent' => [null],
                'history' => ['a:1:{s:12:"bcb_ehemalig";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 7,
                'user_login' => 'usertest7',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest7',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest7',
            ];
            $wpUser->ID = 7;
            $wpUser->caps = [
                'bcb_ehemalig' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['administrator'];
            $wpUser->allcaps = ['read' => null, 'administrator' => 1];

            $wpUsers[7] = $wpUser;

            $wpUsersMeta[7] = [
                'nickname' => ['usertest7'],
                'first_name' => ['Test 7'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:1:{s:13:"administrator";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test7@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [null],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:1:{s:13:"administrator";a:2:{s:9:"date_from";s:10:"' . date('Y-m-d', $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 8,
                'user_login' => 'usertest8',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest8',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest8',
            ];
            $wpUser->ID = 8;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
                'bcb_tourenchef' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied', 'bcb_tourenchef'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1, 'bcb_tourenchef' => 1];

            $wpUsers[8] = $wpUser;

            $wpUsersMeta[8] = [
                'nickname' => ['usertest8'],
                'first_name' => ['Test 8'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:2:{s:17:"bcb_aktivmitglied";b:1;s:14:"bcb_tourenchef";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test8@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [null],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:2:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}s:14:"bcb_tourenchef";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 9,
                'user_login' => 'usertest9',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest9',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest9',
            ];
            $wpUser->ID = 9;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
                'bcb_materialchef' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied', 'bcb_materialchef'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1, 'bcb_materialchef' => 1];

            $wpUsers[9] = $wpUser;

            $wpUsersMeta[9] = [
                'nickname' => ['usertest9'],
                'first_name' => ['Test 9'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:2:{s:17:"bcb_aktivmitglied";b:1;s:16:"bcb_materialchef";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test9@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [null],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:2:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}s:16:"bcb_materialchef";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}}'],
            ];

            $wpUser = new \stdClass();
            $wpUser->data = [
                'ID' => 10,
                'user_login' => 'usertest9',
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest9',
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest10',
            ];
            $wpUser->ID = 10;
            $wpUser->caps = [
                'bcb_aktivmitglied' => 1,
                'bcb_leiter' => 1,
            ];
            $wpUser->cap_key = 'wp_capabilities';
            $wpUser->roles = ['bcb_aktivmitglied', 'bcb_leiter'];
            $wpUser->allcaps = ['read' => null, 'bcb_aktivmitglied' => 1, 'bcb_leiter' => 1];

            $wpUsers[10] = $wpUser;

            $wpUsersMeta[10] = [
                'nickname' => ['usertest10'],
                'first_name' => ['Test 10'],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => ['a:2:{s:17:"bcb_aktivmitglied";b:1;s:10:"bcb_leiter";b:1;}'],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [null],
                'program_shipment' => [1],
                'company' => [null],
                'gender' => ['M'],
                'address_addition' => ['Postfach'],
                'street' => ['Teststrasse 1'],
                'zip' => [9999],
                'location' => ['Testlingen'],
                'phone_private' => ['031 123 45 67'],
                'phone_work' => ['031 890 12 34'],
                'phone_mobile' => ['079 567 89 01'],
                'email' => ['test10@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [null],
                'main_address' => [null],
                'mail_sent' => [null],
                'history' => ['a:2:{s:17:"bcb_aktivmitglied";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}s:10:"bcb_leiter";a:2:{s:9:"date_from";s:10:"' . date("Y-m-d", $tm) . '";s:7:"date_to";N;}}'],
            ];
        }


        /**
         * @test
         */
        public function programShipmentOneEqualsToYes()
        {
            $user = new User();
            $user->program_shipment = '1';
            $this->assertEquals('Ja', $user->program_shipment);
        }

        /**
         * @test
         */
        public function programShipmentZeroEqualsToNo()
        {
            $user = new User();
            $user->program_shipment = '0';
            $this->assertEquals('Nein', $user->program_shipment);
        }

        /**
         * @test
         */
        public function findWithAddressRoleDontAllowWPUsersToBeFound(){
            /*
            $user = User::find(1);
            $this->assertEquals(1, $user->ID);
            */
        }

        /**
         * @test
         */
        public function findWithoutAddressRoleDontAllowWPUsersToBeFound(){
            /*
            global $wpUsers;
            $user = User::find(7);
            $this->assertEquals(null, $user);
            */
        }

        /**
         * @test
         */
        public function findWithoutAddressRoleAllowWPUsersToBeFound(){
            /*
            global $wpUsers;
            $user = User::find(7, true);
            $this->assertEquals(7, $user->ID);
            */
        }

        /**
         * @test
         */
        public function findCurrentUser(){
            /*
            $user = User::findCurrent();
            $this->assertEquals(1, $user->ID);
            */
        }

        /**
         * @test
         */
        public function findAllWithoutSpouse(){
            /*
            $users = User::findAllWithoutSpouse();
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }
            $this->assertEquals(7, count($ids));
            $this->assertContains(1, $ids); //user 1 is Aktivmitglied
            $this->assertContains(2, $ids); //user 2 is Aktivmitglied
            $this->assertNotContains(3, $ids); //user 3 is Aktivmitglied and spouse of user 2
            $this->assertContains(4, $ids); //user 4 is Inserent
            $this->assertContains(5, $ids); //user 5 is Ehemalig
            $this->assertNotContains(6, $ids); //user 6 is Ehemalig and spouse of user 7
            $this->assertNotContains(7, $ids); //user 7 is Administrator (no address role)
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
            $this->assertContains(9, $ids); //user 9 is Aktivmitglied and Materialchef
            $this->assertContains(10, $ids); //user 10 is Aktivmitglied and Leiter
            */
        }

        /**
         * @test
         */
        public function findAll(){
            /*
            $users = User::findAll();
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }

            $this->assertEquals(9, count($ids));
            $this->assertContains(1, $ids); //user 1 is Aktivmitglied
            $this->assertContains(2, $ids); //user 2 is Aktivmitglied
            $this->assertContains(3, $ids); //user 3 is Aktivmitglied and spouse of user 2
            $this->assertContains(4, $ids); //user 4 is Inserent
            $this->assertContains(5, $ids); //user 5 is Ehemalig
            $this->assertContains(6, $ids); //user 6 is Ehemalig and spouse of user 7
            $this->assertNotContains(7, $ids); //user 7 is Administrator (no address role)
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
            $this->assertContains(9, $ids); //user 9 is Aktivmitglied and Materialchef
            $this->assertContains(10, $ids); //user 10 is Aktivmitglied and Leiter
            */
        }

        /**
         * @test
         */
        public function findByLogin(){
            /*
            $user = User::findByLogin('usertest');
            $this->assertEquals(1, $user->ID);
            */
        }

        /**
         * @test
         */
        public function findMitgliederWithoutSpouse(){
            /*
            $users = User::findMitgliederWithoutSpouse();
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }

            $this->assertEquals(5, count($ids));
            $this->assertContains(1, $ids); //user 1 is Aktivmitglied
            $this->assertContains(2, $ids); //user 2 is Aktivmitglied
            $this->assertNotContains(3, $ids); //user 3 is Aktivmitglied and spouse of user 2
            $this->assertNotContains(4, $ids); //user 4 is Inserent
            $this->assertNotContains(5, $ids); //user 5 is Ehemalig
            $this->assertNotContains(6, $ids); //user 6 is Ehemalig and spouse of user 7
            $this->assertNotContains(7, $ids); //user 7 is Administrator (no address role)
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
            $this->assertContains(9, $ids); //user 9 is Aktivmitglied and Materialchef
            $this->assertContains(10, $ids); //user 10 is Aktivmitglied and Leiter
            */
        }

        /**
         * @test
         */
        public function findMitglieder(){
            /*
            $users = User::findMitglieder();
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }

            $this->assertEquals(6, count($ids));
            $this->assertContains(1, $ids); //user 1 is Aktivmitglied
            $this->assertContains(2, $ids); //user 2 is Aktivmitglied
            $this->assertContains(3, $ids); //user 3 is Aktivmitglied and spouse of user 2
            $this->assertNotContains(4, $ids); //user 4 is Inserent
            $this->assertNotContains(5, $ids); //user 5 is Ehemalig
            $this->assertNotContains(6, $ids); //user 6 is Ehemalig and spouse of user 7
            $this->assertNotContains(7, $ids); //user 7 is Administrator (no address role)
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
            $this->assertContains(9, $ids); //user 9 is Aktivmitglied and Materialchef
            $this->assertContains(10, $ids); //user 10 is Aktivmitglied and Leiter
            */
        }

        /**
         * @test
         */
        public function findVorstand(){
            /*
            $vorstand = User::findVorstand();
            $this->assertEquals(1, count($vorstand));
            $this->assertEquals('Tourenchef', $vorstand[0]['title']);
            $this->assertEquals(1, count($vorstand[0]['users']));
            $this->assertEquals('User Test 8', $vorstand[0]['users'][0]->name);
            $this->assertEquals(3, count($vorstand[0]['users'][0]->address));
            $this->assertEquals('Postfach', $vorstand[0]['users'][0]->address[0]);
            $this->assertEquals('Teststrasse 1', $vorstand[0]['users'][0]->address[1]);
            $this->assertEquals('9999 Testlingen', $vorstand[0]['users'][0]->address[2]);
            $this->assertEquals('031 123 45 67', $vorstand[0]['users'][0]->phone_private);
            $this->assertEquals('031 890 12 34', $vorstand[0]['users'][0]->phone_work);
            $this->assertEquals('079 567 89 01', $vorstand[0]['users'][0]->phone_mobile);
            $this->assertEquals('test8@user.com', $vorstand[0]['users'][0]->email);
            */
        }

        /**
         * @test
         */
        public function findErweiterterVorstand(){
            /*
            $vorstand = User::findErweiterterVorstand();
            $this->assertEquals(1, count($vorstand));
            $this->assertEquals('Materialchef', $vorstand[0]['title']);
            $this->assertEquals(1, count($vorstand[0]['users']));
            $this->assertEquals('User Test 9', $vorstand[0]['users'][0]->name);
            */
        }

        /**
         * @test
         */
        public function findLeiter(){
            /*
            $leiter = User::findLeiter();
            $this->assertEquals(2, count($leiter));
            $this->assertEquals('Tourenchef', $leiter[0]['title']);
            $this->assertEquals(1, count($leiter[0]['users']));
            $this->assertEquals('User Test 8', $leiter[0]['users'][0]->name);

            $this->assertEquals('Leiter', $leiter[1]['title']);
            $this->assertEquals(1, count($leiter[1]['users']));
            $this->assertEquals('User Test 10', $leiter[1]['users'][0]->name);
            */
        }

        /**
         * @test
         */
        public function findLeiterJugend(){
            global $wpUsers;
            $wpUsers[1]->roles[] = 'bcb_leiter_jugend';
            $wpUsers[2]->roles[] = 'bcb_tourenchef_jugend';
            $leiter = User::findLeiterJugend();
            $this->assertEquals(2, count($leiter));
            $this->assertEquals('Tourenchef Jugend', $leiter[0]['title']);
            $this->assertEquals(1, count($leiter[0]['users']));
            $this->assertEquals('User Test 2', $leiter[0]['users'][0]->name);

            $this->assertEquals('Leiter Jugend', $leiter[1]['title']);
            $this->assertEquals(1, count($leiter[1]['users']));
            $this->assertEquals('User Test', $leiter[1]['users'][0]->name);
        }

        /**
         * @test
         */
        public function findByRoles(){
            $users = User::findByRoles(['bcb_tourenchef', 'bcb_materialchef']);
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }

            $this->assertEquals(2, count($users));
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
            $this->assertContains(9, $ids); //user 9 is Aktivmitglied and Materialchef
        }

        /**
         * @test
         */
        public function findByRole(){
            $users = User::findByRole('bcb_tourenchef');
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }

            $this->assertEquals(1, count($users));
            $this->assertContains(8, $ids); //user 8 is Aktivmitglied and Tourenchef
        }

        /**
         * @test
         */
        public function saveNewWithoutFunctionaryRole(){

        }

        /**
         * @test
         */
        public function saveNewWithFunctionaryRole(){

        }

        /**
         * @test
         */
        public function saveNewWithoutAddressRole(){
                //expect exception
        }
    }
}