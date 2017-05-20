<?php
namespace {
    require_once __DIR__ . '/wp_mocks.php';
}

namespace BergclubPlugin\Tests\MVC\Models {

    use BergclubPlugin\MVC\Models\Role;
    use PHPUnit\Framework\TestCase;

    class RoleTest extends TestCase
    {
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
            foreach ($rolesByType as $type => $rolesArray) {
                foreach ($rolesArray as $key => $name) {
                    $wp_roles->roles[$key] = ['name' => $name, 'capabilities' => ['read' => true]];
                }
            }
        }

        /**
         * @test
         */
        public function roleAddress()
        {
            $role = new Role(Role::TYPE_ADDRESS, 'test_a', 'Test A');
            $this->assertEquals(Role::TYPE_ADDRESS, $role->getType());
            $this->assertEquals('bcb_test_a', $role->getKey());
            $this->assertEquals('Test A', $role->getName());
        }

        /**
         * @test
         */
        public function roleFunctionary()
        {
            $role = new Role(Role::TYPE_FUNCTIONARY, 'test_f', 'Test F');
            $this->assertEquals(Role::TYPE_FUNCTIONARY, $role->getType());
            $this->assertEquals('bcb_test_f', $role->getKey());
            $this->assertEquals('Test F', $role->getName());
        }

        /**
         * @test
         */
        public function roleSystem()
        {
            $role = new Role(Role::TYPE_SYSTEM, 'test_s', 'Test S');
            $this->assertEquals(Role::TYPE_SYSTEM, $role->getType());
            $this->assertEquals('bcb_test_s', $role->getKey());
            $this->assertEquals('Test S', $role->getName());
        }

        /**
         * @test
         */
        public function findAddress()
        {
            $role = Role::find('bcb_aktivmitglied');
            $this->assertEquals(Role::TYPE_ADDRESS, $role->getType());
            $this->assertEquals('bcb_aktivmitglied', $role->getKey());
            $this->assertEquals('Aktivmitglied', $role->getName());
        }

        /**
         * @test
         */
        public function findFunctionary()
        {
            $role = Role::find('bcb_tourenchef');
            $this->assertEquals(Role::TYPE_FUNCTIONARY, $role->getType());
            $this->assertEquals('bcb_tourenchef', $role->getKey());
            $this->assertEquals('Tourenchef/in', $role->getName());
        }

        /**
         * @test
         */
        public function findSystem()
        {
            $role = Role::find('administrator');
            $this->assertEquals(Role::TYPE_SYSTEM, $role->getType());
            $this->assertEquals('bcb_administrator', $role->getKey());
            $this->assertEquals('administrator', $role->getName());
        }

        /**
         * @test
         */
        public function findAll()
        {
            $roles = Role::findAll();
            $this->assertEquals(24, count($roles));
        }

        /**
         * @test
         */
        public function findByType()
        {
            $roles = Role::findByType(Role::TYPE_ADDRESS);
            $this->assertEquals(9, count($roles));

            $roles = Role::findByType(Role::TYPE_FUNCTIONARY);
            $this->assertEquals(14, count($roles));

            $roles = Role::findByType(Role::TYPE_SYSTEM);
            $this->assertEquals(1, count($roles));
        }

        /**
         * @test
         */
        public function save()
        {
            $role = new Role(Role::TYPE_ADDRESS, 'test_a', 'Test A');
            $role->addCapability('cap_a', true);
            $role->addCapability('cap_b', false);
            $role->save();

            $role = Role::find('test_a');

            $this->assertEquals(Role::TYPE_ADDRESS, $role->getType());
            $this->assertEquals('bcb_test_a', $role->getKey());
            $this->assertEquals('Test A', $role->getName());
            $this->assertEquals(['bcb_cap_a' => true, 'bcb_cap_b' => false], $role->getCapabilities());
        }

        /**
         * @test
         */
        public function delete()
        {
            $role = new Role(Role::TYPE_ADDRESS, 'test_a', 'Test A');
            $role->addCapability('cap_a', true);
            $role->addCapability('cap_b', false);
            $role->save();


            $role = Role::find('test_a');
            $this->assertNotNull($role);

            $role->delete();

            $role = Role::find('test_a');
            $this->assertNull($role);
        }

        /**
         * @test
         */
        public function remove()
        {
            $role = new Role(Role::TYPE_ADDRESS, 'test_a', 'Test A');
            $role->addCapability('cap_a', true);
            $role->addCapability('cap_b', false);
            $role->save();


            $role = Role::find('test_a');
            $this->assertNotNull($role);

            Role::remove('bcb_test_a');

            $role = Role::find('test_a');
            print_r($role);
            $this->assertNull($role);
        }


    }
}