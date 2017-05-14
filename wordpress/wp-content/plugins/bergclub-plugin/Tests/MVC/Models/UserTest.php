<?php
namespace {
    require_once __DIR__ . '/wp_mocks.php';
}

namespace BergclubPlugin\Tests\MVC\Models {
    use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;
    use BergclubPlugin\MVC\Models\Role;
    use BergclubPlugin\MVC\Models\User;
    use PHPUnit\Framework\TestCase;

    class UserTest extends TestCase
    {

        protected function createTestUser($id, $roles = ['bcb_aktivmitglied'], $spouse = null, $main_address = null, $leaving_reason = null){
            global $wpUsers;
            global $wpUsersMeta;

            $tm = date("U");

            $history = [];

            $wpUser = new \WP_User();
            $wpUser->data = [
                'ID' => $id,
                'user_login' => 'usertest' . $id,
                'user_pass' => '$P$B374xSqLsoDdG5.zVyvNTy1wJjpoUW.',
                'user_nicename' => 'usertest' . $id,
                'user_email' => null,
                'user_registered' => date('Y-m-d H:i:s', $tm),
                'user_activation_key' => null,
                'user_status' => 0,
                'display_name' => 'usertest' . $id,
            ];

            $wpUser->ID = $id;

            foreach($roles as $role){
                $wpUser->caps[$role] = 1;
                $wpUser->all_caps[$role] = 1;
                $wpUser->roles[] = $role;
                $history[$role] = ['date_from' => date('Y-m-d', $tm), 'date_to' => null];
            }

            $wpUser->allcaps['read'] = null;

            $wpUsers[] = $wpUser;

            $wpUsersMeta[$id] = [
                'nickname' => ['usertest' . $id],
                'first_name' => ['Test ' . $id],
                'last_name' => ['User'],
                'description' => [null],
                'rich_editing' => [true],
                'comment_shortcuts' => [false],
                'admin_color' => ['fresh'],
                'use_ssl' => [0],
                'show_admin_bar_front' => [true],
                'locale' => [null],
                'wp_capabilities' => [serialize($wpUser->roles)],
                'wp_user_level' => [0],
                'dismissed_wp_pointers' => [null],
                'leaving_reason' => [$leaving_reason],
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
                'email' => ['test' . $id . '@user.com'],
                'birthdate' => ['1970-01-01'],
                'comments' => ['Bemerkung'],
                'spouse' => [$spouse],
                'main_address' => [$main_address],
                'mail_sent' => [null],
                'history' => [serialize($history)],
            ];
        }


        /**
         * @Before
         */
        public function setUp()
        {
            global $wpOptions;
            $wpOptions['bcb_roles'] = unserialize('a:2:{s:7:"address";a:9:{s:15:"bcb_institution";s:11:"Institution";s:12:"bcb_inserent";s:8:"Inserent";s:15:"bcb_interessent";s:11:"Interessent";s:22:"bcb_interessent_jugend";s:18:"Interessent Jugend";s:17:"bcb_aktivmitglied";s:13:"Aktivmitglied";s:24:"bcb_aktivmitglied_jugend";s:20:"Aktivmitglied Jugend";s:17:"bcb_ehrenmitglied";s:13:"Ehrenmitglied";s:12:"bcb_ehemalig";s:8:"Ehemalig";s:16:"bcb_freimitglied";s:12:"Freimitglied";}s:11:"functionary";a:14:{s:10:"bcb_leiter";s:9:"Leiter/in";s:17:"bcb_leiter_jugend";s:16:"Leiter/in Jugend";s:14:"bcb_tourenchef";s:13:"Tourenchef/in";s:21:"bcb_tourenchef_jugend";s:20:"Tourenchef/in Jugend";s:13:"bcb_redaktion";s:9:"Redaktion";s:15:"bcb_sekretariat";s:11:"Sekretariat";s:14:"bcb_mutationen";s:10:"Mutationen";s:9:"bcb_kasse";s:5:"Kasse";s:14:"bcb_praesident";s:13:"Präsident/in";s:16:"bcb_materialchef";s:15:"Materialchef/in";s:23:"bcb_materialchef_jugend";s:22:"Materialchef/in Jugend";s:12:"bcb_js_coach";s:9:"J&S-Coach";s:11:"bcb_versand";s:7:"Versand";s:12:"bcb_internet";s:8:"Internet";}}');
            $wpOptions['bcb_roles']['system'] = ['administrator' => null];

            global $wp_roles;
            $rolesByType = get_option('bcb_roles');
            foreach($rolesByType as $type => $rolesArray){
                foreach($rolesArray as $key => $name){
                    $wp_roles->roles[$key] = ['name' => $name, 'capabilities' => ['read' => true]];
                }
            }

            global $wpMail;
            $wpMail = [];

            global $wpUsers;
            $wpUsers = [];

            $this->createTestUser(1, ['bcb_aktivmitglied']);
            $this->createTestUser(2, ['bcb_aktivmitglied'], 3, true);
            $this->createTestUser(3, ['bcb_aktivmitglied'], 2, false);
            $this->createTestUser(4, ['bcb_inserent']);
            $this->createTestUser(5, ['bcb_ehemalig'], 6, true, 2);
            $this->createTestUser(6, ['bcb_ehemalig'], 5, false, 1);
            $this->createTestUser(7, ['administrator']);
            $this->createTestUser(8, ['bcb_aktivmitglied', 'bcb_tourenchef']);
            $this->createTestUser(9, ['bcb_aktivmitglied', 'bcb_materialchef']);
            $this->createTestUser(10, ['bcb_aktivmitglied', 'bcb_leiter']);
        }

        protected function getNewUser(){
            $user = new User();
            $user->first_name = "Test 11";
            $user->last_name = "User";
            $user->gender = "M";
            $user->address_addition = "Postfach";
            $user->street = "Teststrasse 1";
            $user->zip = 9999;
            $user->location = "Testlingen";
            $user->phone_private = "031 123 45 67";
            $user->phone_work = "031 890 12 34";
            $user->phone_mobile = "079 567 89 01";
            $user->email = "test11@user.com";
            $user->birthdate = "1970-01-01";
            $user->comments = "Bemerkung";

            return $user;
        }

        /**
         * @test
         */
        public function findWithAddressRoleDontAllowWPUsersToBeFound(){
            $user = User::find(1);
            $this->assertEquals(1, $user->ID);
        }

        /**
         * @test
         */
        public function findWithoutAddressRoleDontAllowWPUsersToBeFound(){
            global $wpUsers;
            $user = User::find(7);
            $this->assertEquals(null, $user);
        }

        /**
         * @test
         */
        public function findWithoutAddressRoleAllowWPUsersToBeFound(){
            global $wpUsers;
            $user = User::find(7, true);
            $this->assertEquals(7, $user->ID);
        }

        /**
         * @test
         */
        public function findCurrentUser(){
            $user = User::findCurrent();
            $this->assertEquals(1, $user->ID);
        }

        /**
         * @test
         */
        public function findAllWithoutSpouse(){
            $users = User::findAllWithoutSpouse();
            $ids = [];
            foreach($users as $user){
                $ids[] = $user->ID;
            }
            //$this->assertEquals(7, count($ids));
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
        }

        /**
         * @test
         */
        public function findAll(){
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
        }

        /**
         * @test
         */
        public function findByLogin(){
            $user = User::findByLogin('usertest1');
            $this->assertEquals(1, $user->ID);
        }

        /**
         * @test
         */
        public function findMitgliederWithoutSpouse(){
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
        }

        /**
         * @test
         */
        public function findMitglieder(){
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
        }

        /**
         * @test
         */
        public function findVorstand(){
            $vorstand = User::findVorstand();
            $this->assertEquals(1, count($vorstand));
            $this->assertEquals('Tourenchef/in', $vorstand[0]['title']);
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
        }

        /**
         * @test
         */
        public function findErweiterterVorstand(){
            $vorstand = User::findErweiterterVorstand();
            $this->assertEquals(1, count($vorstand));
            $this->assertEquals('Materialchef/in', $vorstand[0]['title']);
            $this->assertEquals(1, count($vorstand[0]['users']));
            $this->assertEquals('User Test 9', $vorstand[0]['users'][0]->name);
        }

        /**
         * @test
         */
        public function findLeiter(){
            $leiter = User::findLeiter();
            $this->assertEquals(2, count($leiter));
            $this->assertEquals('Tourenchef/in', $leiter[0]['title']);
            $this->assertEquals(1, count($leiter[0]['users']));
            $this->assertEquals('User Test 8', $leiter[0]['users'][0]->name);

            $this->assertEquals('Leiter/in', $leiter[1]['title']);
            $this->assertEquals(1, count($leiter[1]['users']));
            $this->assertEquals('User Test 10', $leiter[1]['users'][0]->name);
        }

        /**
         * @test
         */
        public function findLeiterJugend(){
            $wpUser = get_user_by('ID', 1);
            $wpUser->roles[] = 'bcb_leiter_jugend';
            $wpUser = get_user_by('ID', 2);
            $wpUser->roles[] = 'bcb_tourenchef_jugend';
            $leiter = User::findLeiterJugend();
            $this->assertEquals(2, count($leiter));
            $this->assertEquals('Tourenchef/in Jugend', $leiter[0]['title']);
            $this->assertEquals(1, count($leiter[0]['users']));
            $this->assertEquals('User Test 2', $leiter[0]['users'][0]->name);

            $this->assertEquals('Leiter/in Jugend', $leiter[1]['title']);
            $this->assertEquals(1, count($leiter[1]['users']));
            $this->assertEquals('User Test 1', $leiter[1]['users'][0]->name);
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
            global $wpUsers;
            global $wpUsersMeta;

            /* @var User $user */
            $user = $this->getNewUser();

            $user->addRole(Role::find('bcb_aktivmitglied'));

            $user->save();

            $wpUser = array_values(array_slice($wpUsers, -1))[0];

            $this->assertEquals(11, $wpUser->ID);
            $this->assertEquals(null, $wpUser->data['user_email']); //if the user has no functionary role, the user_email should be null.
            $this->assertEquals("usertest", $wpUser->data['user_login']);
            $this->assertContains('bcb_aktivmitglied', $wpUser->roles);

            $wpUserMeta = $wpUsersMeta[$wpUser->ID];
            $this->assertEquals("Test 11", $wpUserMeta['first_name'][0]);
            $this->assertEquals("User", $wpUserMeta['last_name'][0]);
            $this->assertEquals("M", $wpUserMeta['gender'][0]);
            $this->assertEquals("Postfach", $wpUserMeta['address_addition'][0]);
            $this->assertEquals("Teststrasse 1", $wpUserMeta['street'][0]);
            $this->assertEquals("9999", $wpUserMeta['zip'][0]);
            $this->assertEquals("Testlingen", $wpUserMeta['location'][0]);
            $this->assertEquals("031 123 45 67", $wpUserMeta['phone_private'][0]);
            $this->assertEquals("031 890 12 34", $wpUserMeta['phone_work'][0]);
            $this->assertEquals("079 567 89 01", $wpUserMeta['phone_mobile'][0]);
            $this->assertEquals("test11@user.com", $wpUserMeta['email'][0]);
            $this->assertEquals("1970-01-01", $wpUserMeta['birthdate'][0]);
            $this->assertEquals("Bemerkung", $wpUserMeta['comments'][0]);
        }

        /**
         * @test
         */
        public function saveNewWithoutAddressRole(){
            $this->expectException(NotABergClubUserException::class);

            /* @var User $user */
            $user = $this->getNewUser();

            $user->save();
        }

        /**
         * @test
         */
        public function saveNewWithFunctionaryRole(){
            global $wpMail;
            global $wpUsers;

            $user = new User();
            $user->first_name = "Test 11";
            $user->last_name = "User";
            $user->gender = "M";
            $user->address_addition = "Postfach";
            $user->street = "Teststrasse 1";
            $user->zip = 9999;
            $user->location = "Testlingen";
            $user->phone_private = "031 123 45 67";
            $user->phone_work = "031 890 12 34";
            $user->phone_mobile = "079 567 89 01";
            $user->email = "test11@user.com";
            $user->birthdate = "1970-01-01";
            $user->comments = "Bemerkung";

            $user->addRole(Role::find('bcb_aktivmitglied'));
            $user->addRole(Role::find('bcb_tourenchef'));

            $user->save();

            $wpUser = array_values(array_slice($wpUsers, -1))[0];
            $this->assertContains('bcb_aktivmitglied', $wpUser->roles);
            $this->assertContains('bcb_tourenchef', $wpUser->roles);

            $this->assertEquals(1, count($wpMail));
            $this->assertEquals('"Test 11 User <test11@user.com>"', $wpMail[0]['to']);
            $this->assertEquals('Dein Bergclub Bern Login', $wpMail[0]['subject']);
        }

        /**
         * @test
         */
        public function addAndRemoveRole(){
            global $wpMail;
            global $wpUsers;

            $user = User::find(1);
            $user->addRole(Role::find('bcb_tourenchef'));

            $user->save();

            $user->removeRole(Role::find('bcb_tourenchef'));

            $user->save();


            $wpUser = get_user_by('ID', 1);
            $this->assertEquals(null, $wpUser->data['user_email']); //if the user has no functionary role, the user_email should be null.
            $this->assertContains('bcb_aktivmitglied', $wpUser->roles);
            $this->assertNotContains('bcb_tourenchef', $wpUser->roles);

            $this->assertEquals(1, count($wpMail));
        }

        /**
         * @test
         */
        public function delete()
        {
            global $wpUsers;

            $user = User::find(1);
            $user->delete();

            $this->assertNull(get_user_by('ID', 1));
        }

        /**
         * @test
         */
        public function changeAddressRole()
        {
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_inserent', $user->roles);

            $user->addRole(Role::find('bcb_inserent'));

            $this->assertArrayNotHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayHasKey('bcb_inserent', $user->roles);

            $this->assertEquals(2, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertEquals('Aktivmitglied', $user->history['bcb_aktivmitglied']['name']);
            $this->assertNotNull($user->history['bcb_aktivmitglied']['date_from']);
            $this->assertNotNull($user->history['bcb_aktivmitglied']['date_to']);

            $this->assertArrayHasKey('bcb_inserent', $user->history);
            $this->assertEquals('Inserent', $user->history['bcb_inserent']['name']);
            $this->assertNotNull($user->history['bcb_inserent']['date_from']);
            $this->assertNull($user->history['bcb_inserent']['date_to']);
        }

        /**
         * @test
         */
        public function addFunctionaryRole()
        {
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_tourenchef', $user->roles);

            $user->addRole(Role::find('bcb_tourenchef'));

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayHasKey('bcb_tourenchef', $user->roles);

            $this->assertEquals(2, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertEquals('Aktivmitglied', $user->history['bcb_aktivmitglied']['name']);
            $this->assertNotNull($user->history['bcb_aktivmitglied']['date_from']);
            $this->assertNull($user->history['bcb_aktivmitglied']['date_to']);

            $this->assertArrayHasKey('bcb_tourenchef', $user->history);
            $this->assertEquals('Tourenchef/in', $user->history['bcb_tourenchef']['name']);
            $this->assertNotNull($user->history['bcb_tourenchef']['date_from']);
            $this->assertNull($user->history['bcb_tourenchef']['date_to']);
        }

        /**
         * @test
         */
        public function addFunctionaryRoleWithoutHistoryUpdate()
        {
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_tourenchef', $user->roles);

            $user->addRole(Role::find('bcb_tourenchef'), false);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayHasKey('bcb_tourenchef', $user->roles);

            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
        }

        /**
         * @test
         */
        public function addSystemRoleNotWorking()
        {
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_administrator', $user->roles);

            $user->addRole(Role::find('administrator'));

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_administrator', $user->roles);

            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
        }

        /**
         * @test
         */
        public function addSystemRoleWorking()
        {
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayNotHasKey('bcb_administrator', $user->roles);

            $user->addRole(Role::find('administrator'), true, true);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertArrayHasKey('bcb_administrator', $user->roles);

            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
        }

        /**
         * @test
         */
        public function removeRole(){
            $user = User::find(1);

            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertNull($user->history['bcb_aktivmitglied']['date_to']);

            $user->removeRole(Role::find('bcb_aktivmitglied'));

            $this->assertArrayNotHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertNotNull($user->history['bcb_aktivmitglied']['date_to']);
        }

        /**
         * @test
         */
        public function removeRoleWithoutUpdatingHistory(){
            $user = User::find(1);

            $history = $user->history;

            $user->removeRole(Role::find('bcb_aktivmitglied'), false);

            $this->assertEquals($history, $user->history);
        }

        /**
         * @test
         */
        public function removeSystemRole(){
            $user = User::find(1);
            $history = $user->history;

            $user->addRole(Role::find('administrator'), true, true);

            $this->assertArrayHasKey('bcb_administrator', $user->roles);
            $this->assertEquals($history, $user->history);

            $user->removeRole(Role::find('administrator'));

            $this->assertArrayNotHasKey('bcb_administrator', $user->roles);
            $this->assertEquals($history, $user->history);
        }

        /**
         * @test
         */
        public function hasCapability(){
            $user = User::find(1);
            $this->assertTrue($user->hasCapability('read'));
        }

        /**
         * @test
         */
        public function hasRole(){
            $user = User::find(1);

            $this->assertTrue($user->hasRole('bcb_aktivmitglied'));
            $this->assertFalse($user->hasRole('bcb_tourenchef'));
        }

        /**
         * @test
         */
        public function unsetSpouse(){
            $user = User::find(2);

            $this->assertNotNull($user->spouse);
            $this->assertTrue($user->main_address);

            $user->unsetSpouse();
            $this->assertNull($user->spouse);
            $this->assertNull($user->main_address);
        }

        /**
         * @test
         */
        public function setHistory(){
            $user = User::find(1);

            $history = [
                'bcb_aktivmitglied' => ['date_from' => '28.07.1980', 'date_to' => '07.05.2017'],
                'bcb_tourenchef' => ['date_from' => '28.07.1980', 'date_to' => null],
            ];

            $user->history = $history;

            $this->assertEquals(2, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertEquals($history['bcb_aktivmitglied']['date_from'], $user->history['bcb_aktivmitglied']['date_from']);
            $this->assertEquals($history['bcb_aktivmitglied']['date_to'], $user->history['bcb_aktivmitglied']['date_to']);
            $this->assertArrayHasKey('bcb_tourenchef', $user->history);
            $this->assertEquals($history['bcb_tourenchef']['date_from'], $user->history['bcb_tourenchef']['date_from']);
            $this->assertEquals($history['bcb_tourenchef']['date_to'], $user->history['bcb_tourenchef']['date_to']);
        }

        /**
         * @test
         */
        public function setHistoryWithoutDateTo(){
            $user = User::find(1);

            $history = [
                'bcb_tourenchef' => ['date_from' => '28.07.1980'],
            ];

            $user->history = $history;

            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_tourenchef', $user->history);
            $this->assertEquals($history['bcb_tourenchef']['date_from'], $user->history['bcb_tourenchef']['date_from']);
            $this->assertEquals(null, $user->history['bcb_tourenchef']['date_to']);
        }

        /**
         * @test
         */
        public function setHistoryWithoutDateFrom(){
            $user = User::find(1);

            $history = [
                'bcb_aktivmitglied' => ['date_from' => '28.07.1980', 'date_to' => '07.05.2017'],
                'bcb_tourenchef' => ['date_to' => '07.05.2017'],
                'bcb_materialchef' => ['date_to' => null],
            ];

            $user->history = $history;

            $this->assertEquals(1, count($user->history));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->history);
            $this->assertEquals($history['bcb_aktivmitglied']['date_from'], $user->history['bcb_aktivmitglied']['date_from']);
            $this->assertEquals($history['bcb_aktivmitglied']['date_to'], $user->history['bcb_aktivmitglied']['date_to']);
        }

        /**
         * @test
         */
        public function setHistoryWithoutDates(){
            $user = User::find(1);

            $history = [
                'bcb_tourenchef' => [],
            ];

            $user->history = $history;

            $this->assertEmpty($user->history);
        }

        /**
         * @test
         */
        public function setHistoryWithoutData(){
            $user = User::find(1);

            $history = [];

            $user->history = $history;

            $this->assertEmpty($user->history);
        }


        /**
         * @test
         */
        public function roles(){
            $user = User::find(1);
            $user->addRole(Role::find('bcb_tourenchef'));

            $this->assertEquals(2, count($user->roles));
            $this->assertArrayHasKey('bcb_aktivmitglied', $user->roles);
            $this->assertEquals(Role::find('bcb_aktivmitglied'), $user->roles['bcb_aktivmitglied']);
            $this->assertArrayHasKey('bcb_tourenchef', $user->roles);
            $this->assertEquals(Role::find('bcb_tourenchef'), $user->roles['bcb_tourenchef']);

            $this->assertEquals(1, count($user->functionaryRoles));
            $this->assertArrayHasKey('bcb_tourenchef', $user->functionaryRoles);
            $this->assertEquals(Role::find('bcb_tourenchef'), $user->functionaryRoles['bcb_tourenchef']);

            $this->assertEquals(Role::find('bcb_aktivmitglied'), $user->addressRole);
            $this->assertEquals(Role::find('bcb_aktivmitglied')->getName(), $user->addressRoleName);
            $this->assertEquals(Role::find('bcb_aktivmitglied')->getKey(), $user->addressRoleKey);
        }

        /**
         * @test
         */
        public function spouse(){
            $user = User::find(2);
            $spouse = User::find(3);

            $this->assertEquals($spouse->ID, $user->spouse->ID);
            $this->assertEquals($user, $spouse->spouse);
        }

        /**
         * @test
         */
        public function spouseName(){
            $user = User::find(2);
            $spouse = User::find(3);

            $this->assertEquals($spouse->displayName, $user->spouseName);
            $this->assertEquals('User Test 3', $user->spouseName);
        }

        /**
         * @test
         */
        public function setSpouse(){
            $user = User::find(8);
            $spouse = User::find(9);

            $user->spouse = $spouse;
            $spouse->spouse = $user;

            $this->assertEquals($spouse->ID, $user->spouse->ID);
            $this->assertEquals($user->ID, $spouse->spouse->ID);
        }

        /**
         * @test
         */
        public function setSpouseId(){
            $user = User::find(8);
            $spouse = User::find(9);

            $user->spouseId = $spouse->ID;
            $spouse->spouseId = $user->ID;

            $this->assertEquals($spouse->ID, $user->spouse->ID);
            $this->assertEquals($user->ID, $spouse->spouse->ID);
        }

        /**
         * @test
         */
        public function leavingReason(){
            $user = new User();
            $user->leaving_reason = null;
            $this->assertNull($user->leaving_reason);

            $user->leaving_reason = 1;
            $this->assertEquals('Ausgetreten', $user->leaving_reason);

            $user->leaving_reason = 2;
            $this->assertEquals('Verstorben', $user->leaving_reason);

            $this->expectException(\UnexpectedValueException::class);
            $user->leaving_reason = 3;
        }

        /**
         * @test
         */
        public function programShipment(){
            $user = new User();
            $user->program_shipment = null;
            $this->assertEquals(null, $user->program_shipment);

            $user->program_shipment = 0;
            $this->assertEquals('Nein', $user->program_shipment);

            $user->program_shipment = 1;
            $this->assertEquals('Ja', $user->program_shipment);

            $this->expectException(\UnexpectedValueException::class);
            $user->program_shipment = 2;
        }

        /**
         * @test
         */
        public function gender(){
            $user = new User();
            $user->gender = null;
            $this->assertEquals(null, $user->gender);

            $user->gender = 'M';
            $this->assertEquals('Herr', $user->gender);

            $user->gender = 'F';
            $this->assertEquals('Frau', $user->gender);

            $this->expectException(\UnexpectedValueException::class);
            $user->gender = 'U';
        }

        /**
         * @test
         */
        public function displayName(){
            $user = new User();
            $this->assertEmpty($user->displayName);

            $user->first_name = 'Fritz';
            $this->assertEquals('Fritz', $user->displayName);

            $user->last_name = 'Muster';
            $this->assertEquals('Muster Fritz', $user->displayName);

            $user->first_name = null;
            $this->assertEquals('Muster', $user->displayName);
        }

        /**
         * @test
         */
        public function address(){
            $user = new User();
            $this->assertEmpty($user->address);

            $user->address_addition = 'Affix';
            $this->assertEquals('Affix', join(', ', $user->address));

            $user->street = 'Street';
            $this->assertEquals('Affix, Street', join(', ', $user->address));

            $user->zip = 'Zip';
            $this->assertEquals('Affix, Street', join(', ', $user->address));

            $user->location = 'Location';
            $this->assertEquals('Affix, Street, Zip Location', join(', ', $user->address));

            $user->zip = null;
            $this->assertEquals('Affix, Street, Location', join(', ', $user->address));

            $user->address_addition = null;
            $this->assertEquals('Street, Location', join(', ', $user->address));

            $user->street = null;
            $this->assertEquals('Location', join(', ', $user->address));
        }

        /**
         * @test
         */
        public function birthdate(){
            $user = new User();
            $this->assertNull($user->birthdate);

            $user->birthdate = "28.07.1980";
            $this->assertEquals('28.07.1980', $user->birthdate);

            $user->birthdate = "28.7.80";
            $this->assertEquals('28.07.1980', $user->birthdate);

            $user->birthdate = "07/28/1980";
            $this->assertEquals('28.07.1980', $user->birthdate);

            $user->birthdate = "7/28/80";
            $this->assertEquals('28.07.1980', $user->birthdate);

            $user->birthdate = "1980-07-28";
            $this->assertEquals('28.07.1980', $user->birthdate);

            $user->birthdate = "80-7-28";
            $this->assertEquals('28.07.1980', $user->birthdate);
        }

        /**
         * @test
         */
        public function userName(){
            $firstName = md5(time() . uniqid());
            $lastName = "User";

            $user = new User();
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->addRole(Role::find('bcb_aktivmitglied'));
            $user->save();

            $userName = $user->user_login;

            $this->assertEquals(strtolower(substr($lastName.$firstName, 0, 8)), $userName);

            for($i = 1; $i <= 100; $i++){
                $user = new User();
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->addRole(Role::find('bcb_aktivmitglied'));
                $user->save();
                $this->assertEquals($userName . $i, $user->user_login);
            }
        }
    }
}