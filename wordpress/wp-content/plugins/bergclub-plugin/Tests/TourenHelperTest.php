<?php

namespace {
    require_once __DIR__ . '/wp_bcb_functions_mocks.php';
}


namespace BergclubPlugin\Tests {

    use BergclubPlugin\MVC\Models\User;
    use BergclubPlugin\Tests\Mocks\UserMock;
    use BergclubPlugin\TourenHelper;
    use PHPUnit\Framework\TestCase;

    class TourenHelperTest extends TestCase
    {

        public static function setUpBeforeClass(){
            TourenHelper::setUserClassStatic("BergclubPlugin\\Tests\\Mocks\\UserMock");
            TourenHelper::setOptionClassStatic("BergclubPlugin\\Tests\\Mocks\\OptionMock");
        }

        /**
         * @test
         */
        function getIsYouth(){
            global $currentMetaValue;

            $currentMetaValue = 0;
            $this->assertEquals('BCB', TourenHelper::getIsYouth(1));

            $currentMetaValue = 1;
            $this->assertEquals('Jugend', TourenHelper::getIsYouth(1));

            $currentMetaValue = 2;
            $this->assertEquals('Beides', TourenHelper::getIsYouth(1));
        }

        /**
         * @test
         */
        function getIsYouthRaw(){
            global $currentMetaValue;

            $currentMetaValue = 0;
            $this->assertEquals(0, TourenHelper::getIsYouthRaw(1));

            $currentMetaValue = 1;
            $this->assertEquals(1, TourenHelper::getIsYouthRaw(1));

            $currentMetaValue = 2;
            $this->assertEquals(2, TourenHelper::getIsYouthRaw(1));
        }

        /**
         * @test
         */
        function getDateDisplayShort(){
            global $currentMetaValue;

            $currentMetaValue = [];

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $this->assertEquals('01.01.', TourenHelper::getDateDisplayShort(1));

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $currentMetaValue['_dateTo'] = '2.1.2017';
            $this->assertEquals('01.01. - 02.01.', TourenHelper::getDateDisplayShort(1));

        }

        /**
         * @test
         */
        function getIsSeveralDays(){
            global $currentMetaValue;

            $currentMetaValue = [];

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $this->assertEquals(false, TourenHelper::getIsSeveralDays(1));

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $currentMetaValue['_dateTo'] = '1.1.2017';
            $this->assertEquals(false, TourenHelper::getIsSeveralDays(1));

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $currentMetaValue['_dateTo'] = '2.1.2017';
            $this->assertEquals(true, TourenHelper::getIsSeveralDays(1));

        }

        /**
         * @test
         */
        function getDateFrom(){
            global $currentMetaValue;

            $currentMetaValue = [];

            $currentMetaValue['_dateFrom'] = '1.1.2017';
            $this->assertEquals('01.01.2017', TourenHelper::getDateFrom(1));
        }

        /**
         * @test
         */
        function getDateTo(){
            global $currentMetaValue;

            $currentMetaValue = [];

            $currentMetaValue['_dateTo'] = '1.1.2017';
            $this->assertEquals('01.01.2017', TourenHelper::getDateTo(1));
        }

        /**
         * @test
         */
        function getDisplayDateFull(){
            global $currentMetaValue;

            $currentMetaValue = [];

            $currentMetaValue['_dateFrom'] = '31.12.2017';
            $currentMetaValue['_dateTo'] = '1.1.2018';
            $this->assertEquals('31.12.2017 - 01.01.2018', TourenHelper::getDateDisplayFull(1));

            $currentMetaValue['_dateFrom'] = '30.5.2017';
            $currentMetaValue['_dateTo'] = '1.6.2017';
            $this->assertEquals('30.05. - 01.06.2017', TourenHelper::getDateDisplayFull(1));

            $currentMetaValue['_dateFrom'] = '1.5.2017';
            $currentMetaValue['_dateTo'] = '2.5.2017';
            $this->assertEquals('01. - 02.05.2017', TourenHelper::getDateDisplayFull(1));

            $currentMetaValue['_dateFrom'] = '1.5.2017';
            $currentMetaValue['_dateTo'] = '1.5.2017';
            $this->assertEquals('01.05.2017', TourenHelper::getDateDisplayFull(1));
        }

        /**
         * @test
         */
        function getLeader(){
            global $currentMetaValue;
            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Muster';
            $user->first_name = 'Fritz';

            UserMock::$find = $user;

            $this->assertEquals('Muster Fritz', TourenHelper::getLeader(1));
        }

        /**
         * @test
         */
        function getCoLeader(){
            global $currentMetaValue;
            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Müller';
            $user->first_name = 'Sabine';

            UserMock::$find = $user;

            $this->assertEquals('Müller Sabine', TourenHelper::getCoLeader(1));
        }

        /**
         * @test
         */
        function getLeaderAndCoLeader(){
            global $currentMetaValue;
            $currentMetaValue = 1;

            $leader = new UserMock();
            $leader->last_name = 'Meier';
            $leader->first_name = 'Peter';

            $coLeader = new UserMock();
            $coLeader->last_name = 'Stuber';
            $coLeader->first_name = 'Hans';

            UserMock::$find = [$leader, $coLeader];

            $this->assertEquals('Meier Peter, Stuber Hans (Co-Leiter)', TourenHelper::getLeaderAndCoLeader(1));
        }

        /**
         * @test
         */
        function getSignupUntil(){
            global $currentMetaValue;

            $currentMetaValue = '1.7.2017';
            $this->assertEquals('01.07.2017', TourenHelper::getSignupUntil(1));
        }

        /**
         * @test
         */
        function getSignupToFullData(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';
            $user->email = 'verena.schwarz@bluewin.ch';
            $user->phone_private = '031 123 45 67';
            $user->phone_work = '031 890 12 34';
            $user->phone_mobile = '079 567 89 01';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena, <a class=\'email\' data-id=\'dmVyZW5hLnNjaHdhcnpAYmx1ZXdpbi5jaA==\'></a>, 031 123 45 67 (P), 031 890 12 34 (G), 079 567 89 01 (M)', TourenHelper::getSignupTo(1));
        }

        /**
         * @test
         */
        function getSignupToEmailAndMobile(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';
            $user->email = 'verena.schwarz@bluewin.ch';
            $user->phone_mobile = '079 567 89 01';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena, <a class=\'email\' data-id=\'dmVyZW5hLnNjaHdhcnpAYmx1ZXdpbi5jaA==\'></a>, 079 567 89 01 (M)', TourenHelper::getSignupTo(1));
        }

        /**
         * @test
         */
        function getSignupToEmail(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';
            $user->email = 'verena.schwarz@bluewin.ch';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena, <a class=\'email\' data-id=\'dmVyZW5hLnNjaHdhcnpAYmx1ZXdpbi5jaA==\'></a>', TourenHelper::getSignupTo(1));
        }

        /**
         * @test
         */
        function getSignupToPhonePrivate(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';
            $user->phone_private = '031 123 45 67';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena, 031 123 45 67 (P)', TourenHelper::getSignupTo(1));
        }

        /**
         * @test
         */
        function getSignupToNoContactData(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena', TourenHelper::getSignupTo(1));
        }

        /**
         * @test
         */
        function getSignupToNoLinks(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $user = new UserMock();
            $user->last_name = 'Schwarz';
            $user->first_name = 'Verena';
            $user->email = 'verena.schwarz@bluewin.ch';
            $user->phone_private = '031 123 45 67';
            $user->phone_work = '031 890 12 34';
            $user->phone_mobile = '079 567 89 01';

            UserMock::$find = $user;

            $this->assertEquals('Schwarz Verena, verena.schwarz@bluewin.ch, 031 123 45 67 (P), 031 890 12 34 (G), 079 567 89 01 (M)', TourenHelper::getSignupToNoLinks(1));
        }

        /**
         * @test
         */
        function getMeetpointOne(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $this->assertEquals('Bern HB, Treffpunkt', TourenHelper::getMeetpoint(1));
        }

        /**
         * @test
         */
        function getMeetpointTwo(){
            global $currentMetaValue;

            $currentMetaValue = 2;

            $this->assertEquals('Bern HB, auf dem Abfahrtsperron', TourenHelper::getMeetpoint(1));
        }

        /**
         * @test
         */
        function getMeetpointThree(){
            global $currentMetaValue;

            $currentMetaValue = 3;

            $this->assertEquals('Bern HB, auf der Welle', TourenHelper::getMeetpoint(1));
        }

        /**
         * @test
         */
        function getMeetpointDifferent(){
            global $currentMetaValue;

            $currentMetaValue = ['_meetpoint' => 0, '_meetpointDifferent' => 'Zytglogge'];

            $this->assertEquals('Zytglogge', TourenHelper::getMeetpoint(1));
        }

        /**
         * @test
         */
        function getRequirementsConditionalZero(){
            global $currentMetaValue;

            $currentMetaValue = 0;

            $this->assertNull(TourenHelper::getRequirementsConditional(1));
        }

        /**
         * @test
         */
        function getRequirementsConditionalOne(){
            global $currentMetaValue;

            $currentMetaValue = 1;

            $this->assertEquals('Leicht', TourenHelper::getRequirementsConditional(1));
        }

        /**
         * @test
         */
        function getRequirementsConditionalTwo(){
            global $currentMetaValue;

            $currentMetaValue = 2;

            $this->assertEquals('Mittel', TourenHelper::getRequirementsConditional(1));
        }

        /**
         * @test
         */
        function getRequirementsConditionalThree(){
            global $currentMetaValue;

            $currentMetaValue = 3;

            $this->assertEquals('Schwer', TourenHelper::getRequirementsConditional(1));
        }

        /**
         * @test
         */
        function getType(){
            global $currentMetaValue;

            $currentMetaValue = 'bcb_bergtour';
            $this->assertEquals('Bergtour', TourenHelper::getType(1));
        }

        /**
         * @test
         */
        function getTypeWithTechnicalRequirements(){
            global $currentMetaValue;

            $currentMetaValue = ['_type' => 'bcb_bergtour', '_requirementsTechnical' => 'T3'];
            $this->assertEquals('Bergtour, T3', TourenHelper::getTypeWithTechnicalRequirements(1));
        }

        /**
         * @test
         */
        function getRiseUpAndDown(){
            global $currentMetaValue;

            $currentMetaValue = ['_riseUpMeters' => 1200, '_riseDownMeters' => 600];
            $this->assertEquals('<div class="icon icon-up" title="Aufstieg"></div>1200 <div class="icon icon-down" title="Abstieg"></div>600', TourenHelper::getRiseUpAndDown(1));
        }

        /**
         * @test
         */
        function getAdditionalInfo(){
            global $currentMetaValue;

            $currentMetaValue = "Additional Info";
            $this->assertEquals('Additional Info', TourenHelper::getAdditionalInfo(1));
        }

        /**
         * @test
         */
        function getTrainingNo(){
            global $currentMetaValue;

            $currentMetaValue = 0;
            $this->assertEquals('Nein', TourenHelper::getTraining(1));
        }

        /**
         * @test
         */
        function getTrainingYes(){
            global $currentMetaValue;

            $currentMetaValue = 1;
            $this->assertEquals('Ja', TourenHelper::getTraining(1));
        }

        /**
         * @test
         */
        function getJsEventNo(){
            global $currentMetaValue;

            $currentMetaValue = 0;
            $this->assertEquals('Nein', TourenHelper::getJsEvent(1));
        }

        /**
         * @test
         */
        function getJsEventYes(){
            global $currentMetaValue;

            $currentMetaValue = 1;
            $this->assertEquals('Ja', TourenHelper::getJsEvent(1));
        }

        /**
         * @test
         */
        function getProgram(){
            global $currentMetaValue;

            $currentMetaValue = "Program";
            $this->assertEquals('Program', TourenHelper::getProgram(1));
        }

        /**
         * @test
         */
        function getEquipment(){
            global $currentMetaValue;

            $currentMetaValue = "Equipment";
            $this->assertEquals('Equipment', TourenHelper::getProgram(1));
        }
    }
}