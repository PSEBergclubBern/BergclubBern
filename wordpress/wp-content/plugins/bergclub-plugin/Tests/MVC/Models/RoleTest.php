<?php
namespace {
    require_once __DIR__ . '/wp_mocks.php';
}

namespace BergclubPlugin\Tests\MVC\Models {
    use BergclubPlugin\MVC\Exceptions\NotABergClubUserException;
    use BergclubPlugin\MVC\Models\Option;
    use BergclubPlugin\MVC\Models\Role;
    use BergclubPlugin\MVC\Models\User;
    use PHPUnit\Framework\TestCase;

    class RoleTest extends TestCase
    {
        /**
         * @Before
         */
        public function setUp(){
            global $wpRoles;
            $wpRoles = [];
        }

        /**
         * @test
         */
        public function myTest(){

        }
    }
}