<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 13:24
 */

namespace BergclubPlugin\Tests\Export\DataSeeder;


use BergclubPlugin\Tests\Mocks\RoleMock;
use BergclubPlugin\Tests\Mocks\UserMock;

class AddressDataSeeder
{
    static function seedMembers(array &$users, array &$expected)
    {
        static::seedAddresses($users, $expected);

        $roles = [
            ['key' => 'bcb_aktivmitglied', 'name' => "Aktivmitglied"],
            ['key' => 'bcb_aktivmitglied_jugend', 'name' => "Aktivmitglied Jugend"],
            ['key' => 'bcb_ehrenmitglied', 'name' => "Ehrenmitglied"],
            ['key' => 'bcb_freimitglied', 'name' => "Freimitglied"],
        ];

        // we are not interessted in the company entries here so we remove them
        array_shift($users);
        array_shift($users);

        // lets assign address roles to our users
        $roleIndex = 0;
        foreach ($users as &$user) {
            $user->address_role = new RoleMock($roles[$roleIndex]['key'], $roles[$roleIndex]['name']);
            $roleIndex = $roleIndex = count($roles) - 1 ? 0 : $roleIndex++;
        }

        // the first person should be "Präsident/In"
        $users[0]->functionary_roles = [new RoleMock('bcb_praesident', 'Präsident/In')];

        // the second person should be "Tourenchef/In Jugend" and "Leiter"
        $users[1]->functionary_roles = [new RoleMock('bcb_tourenchef_jugend', 'Tourenchef/In Jugend'), new RoleMock('bcb_leiter', 'Leiter')];

        // because members export is not with address lines we override the $expected array
        $expected = [
            0 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Herr',
                    'Nachname' => 'Muster',
                    'Vorname' => 'Fritz',
                    'Zusatz' => 'Postfach 29',
                    'Strasse' => 'Musterstrasse 1',
                    'PLZ' => '9999',
                    'Ort' => 'Musterlingen',
                    'Telefon (P)' => '031 890 12 36',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 03',
                    'Email' => 'fritz.muster@bluewin.ch',
                    'Geburtsdatum' => '02.02.1983',
                    'Ehepartner' => NULL,
                    'Funktionen' => 'Präsident/In',
                ],
            1 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Frau',
                    'Nachname' => 'Sommer',
                    'Vorname' => 'Verena',
                    'Zusatz' => NULL,
                    'Strasse' => 'Wildermettweg 52',
                    'PLZ' => '3006',
                    'Ort' => 'Bern',
                    'Telefon (P)' => '031 890 12 37',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 04',
                    'Email' => 'verena.sommer@bluewin.ch',
                    'Geburtsdatum' => '03.02.1983',
                    'Ehepartner' => NULL,
                    'Funktionen' => 'Tourenchef/In Jugend, Leiter',
                ],
            2 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Herr',
                    'Nachname' => 'Wolfisberger',
                    'Vorname' => 'Samuel',
                    'Zusatz' => NULL,
                    'Strasse' => 'Worbstrasse 1',
                    'PLZ' => '3072',
                    'Ort' => 'Muri bei Bern',
                    'Telefon (P)' => '031 890 12 38',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 05',
                    'Email' => 'samuel.wolfisberger@bluewin.ch',
                    'Geburtsdatum' => '04.02.1983',
                    'Ehepartner' => 'Wolfisberger Konrad',
                    'Funktionen' => NULL,
                ],
            3 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Frau',
                    'Nachname' => 'Guggisberger',
                    'Vorname' => 'Angela',
                    'Zusatz' => NULL,
                    'Strasse' => 'Solothurnerstrasse 1',
                    'PLZ' => '4600',
                    'Ort' => 'Olten',
                    'Telefon (P)' => '031 890 12 39',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 07',
                    'Email' => 'angela.guggisberger@bluewin.ch',
                    'Geburtsdatum' => '07.02.1983',
                    'Ehepartner' => 'Guggisberger Brigitte',
                    'Funktionen' => NULL,
                ],
            4 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Herr',
                    'Nachname' => 'Schmid',
                    'Vorname' => 'Damian',
                    'Zusatz' => NULL,
                    'Strasse' => 'Länggasse 3',
                    'PLZ' => '3000',
                    'Ort' => 'Bern',
                    'Telefon (P)' => '031 890 12 30',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 09',
                    'Email' => 'damian.schmid@bluewin.ch',
                    'Geburtsdatum' => '09.02.1983',
                    'Ehepartner' => 'Schmid Andrea',
                    'Funktionen' => NULL,
                ],
            5 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Frau',
                    'Nachname' => 'Nanzer',
                    'Vorname' => 'Anette',
                    'Zusatz' => NULL,
                    'Strasse' => 'Postgasse 34',
                    'PLZ' => '3600',
                    'Ort' => 'Thun',
                    'Telefon (P)' => '031 890 12 31',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 01',
                    'Email' => 'anette.nanzer@bluewin.ch',
                    'Geburtsdatum' => '11.02.1983',
                    'Ehepartner' => 'Nanzer Paul',
                    'Funktionen' => NULL,
                ],
            6 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Frau',
                    'Nachname' => 'Bieri',
                    'Vorname' => 'Eva',
                    'Zusatz' => NULL,
                    'Strasse' => 'Riedernrain 162',
                    'PLZ' => '3027',
                    'Ort' => 'Bern',
                    'Telefon (P)' => '031 890 12 32',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 03',
                    'Email' => 'eva.bieri@bluewin.ch',
                    'Geburtsdatum' => '13.02.1983',
                    'Ehepartner' => 'Fuhrer Jürg',
                    'Funktionen' => NULL,
                ],
            7 =>
                [
                    'Typ' => 'Aktivmitglied',
                    'Anrede' => 'Herr',
                    'Nachname' => 'Hunziker',
                    'Vorname' => 'Alfred',
                    'Zusatz' => NULL,
                    'Strasse' => 'Attinghausenstrasse 20',
                    'PLZ' => '3014',
                    'Ort' => 'Bern',
                    'Telefon (P)' => '031 890 12 33',
                    'Telefon (G)' => NULL,
                    'Telefon (M)' => '079 567 89 05',
                    'Email' => 'alfred.hunziker@bluewin.ch',
                    'Geburtsdatum' => '15.02.1983',
                    'Ehepartner' => 'Widmer Stephanie',
                    'Funktionen' => NULL,
                ],
        ];
    }

    static function seedAddresses(array &$users, array &$expected)
    {
        $users = [];
        $expected = [];

        //company with no personal contact
        $user = new UserMock();
        $user->company = "Test AG";
        $user->address_addition = "Postfach";
        $user->street = "Teststrasse 1";
        $user->zip = "1234";
        $user->location = "Testlingen";
        $user->phone_private = "031 123 45 67";
        $user->phone_private = "031 890 12 34";
        $user->phone_mobile = "079 567 89 01";
        $user->email = "info@testag.ch";


        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Test AG',
            'Adresszeile 2' => 'Postfach',
            'Adresszeile 3' => 'Teststrasse 1',
            'Adresszeile 4' => '1234 Testlingen',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //company with personal contact
        $user = new UserMock();
        $user->company = "Müller AG";
        $user->gender = "Frau";
        $user->first_name = "Sabine";
        $user->last_name = "Müller";
        $user->address_addition = "Postfach 397";
        $user->street = "Weidenweg 1";
        $user->zip = "4613";
        $user->location = "Rickenbach (SO)";
        $user->phone_private = "031 123 45 68";
        $user->phone_private = "031 890 12 35";
        $user->phone_mobile = "079 567 89 02";
        $user->email = "sabeine.mueller@muellerag.ch";
        $user->birthdate = "01.02.1983";

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Müller AG',
            'Adresszeile 2' => 'Frau Sabine Müller',
            'Adresszeile 3' => 'Postfach 397',
            'Adresszeile 4' => 'Weidenweg 1',
            'Adresszeile 5' => '4613 Rickenbach (SO)',
            'Adresszeile 6' => '',
        ];


        //personal without spouse (male)
        $user = new UserMock();
        $user->gender = "Herr";
        $user->first_name = "Fritz";
        $user->last_name = "Muster";
        $user->address_addition = "Postfach 29";
        $user->street = "Musterstrasse 1";
        $user->zip = "9999";
        $user->location = "Musterlingen";
        $user->phone_private = "031 123 45 69";
        $user->phone_private = "031 890 12 36";
        $user->phone_mobile = "079 567 89 03";
        $user->email = "fritz.muster@bluewin.ch";
        $user->birthdate = "02.02.1983";

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Herr',
            'Adresszeile 2' => 'Fritz Muster',
            'Adresszeile 3' => 'Postfach 29',
            'Adresszeile 4' => 'Musterstrasse 1',
            'Adresszeile 5' => '9999 Musterlingen',
            'Adresszeile 6' => '',
        ];

        //personal without spouse (female)
        $user = new UserMock();
        $user->gender = "Frau";
        $user->first_name = "Verena";
        $user->last_name = "Sommer";
        $user->street = "Wildermettweg 52";
        $user->zip = "3006";
        $user->location = "Bern";
        $user->phone_private = "031 123 45 60";
        $user->phone_private = "031 890 12 37";
        $user->phone_mobile = "079 567 89 04";
        $user->email = "verena.sommer@bluewin.ch";
        $user->birthdate = "03.02.1983";

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Frau',
            'Adresszeile 2' => 'Verena Sommer',
            'Adresszeile 3' => 'Wildermettweg 52',
            'Adresszeile 4' => '3006 Bern',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];


        //personal with spouse same gender (male), same last name
        $user = new UserMock();
        $user->gender = "Herr";
        $user->first_name = "Samuel";
        $user->last_name = "Wolfisberger";
        $user->street = "Worbstrasse 1";
        $user->zip = "3072";
        $user->location = "Muri bei Bern";
        $user->main_address = true;
        $user->phone_private = "031 123 45 61";
        $user->phone_private = "031 890 12 38";
        $user->phone_mobile = "079 567 89 05";
        $user->email = "samuel.wolfisberger@bluewin.ch";
        $user->birthdate = "04.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Konrad";
        $spouse->last_name = "Wolfisberger";
        $spouse->street = "Worbstrasse 1";
        $spouse->zip = "3072";
        $spouse->location = "Muri bei Bern";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 61";
        $spouse->phone_private = "031 890 12 38";
        $spouse->phone_mobile = "079 567 89 06";
        $spouse->email = "konrad.wolfisberger@bluewin.ch";
        $spouse->birthdate = "05.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Herren',
            'Adresszeile 2' => 'Samuel & Konrad Wolfisberger',
            'Adresszeile 3' => 'Worbstrasse 1',
            'Adresszeile 4' => '3072 Muri bei Bern',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //personal with spouse same gender (female), same last name
        $user = new UserMock();
        $user->gender = "Frau";
        $user->first_name = "Angela";
        $user->last_name = "Guggisberger";
        $user->street = "Solothurnerstrasse 1";
        $user->zip = "4600";
        $user->location = "Olten";
        $user->main_address = true;
        $user->phone_private = "031 123 45 62";
        $user->phone_private = "031 890 12 39";
        $user->phone_mobile = "079 567 89 07";
        $user->email = "angela.guggisberger@bluewin.ch";
        $user->birthdate = "07.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Brigitte";
        $spouse->last_name = "Guggisberger";
        $spouse->street = "Solothurnerstrasse 1";
        $spouse->zip = "4600";
        $spouse->location = "Olten";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 62";
        $spouse->phone_private = "031 890 12 39";
        $spouse->phone_mobile = "079 567 89 08";
        $spouse->email = "brigitte.guggisberger@bluewin.ch";
        $spouse->birthdate = "08.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Frauen',
            'Adresszeile 2' => 'Angela & Brigitte Guggisberger',
            'Adresszeile 3' => 'Solothurnerstrasse 1',
            'Adresszeile 4' => '4600 Olten',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //personal with spouse not same gender (male main address), same last name
        $user = new UserMock();
        $user->gender = "Herr";
        $user->first_name = "Damian";
        $user->last_name = "Schmid";
        $user->street = "Länggasse 3";
        $user->zip = "3000";
        $user->location = "Bern";
        $user->main_address = true;
        $user->phone_private = "031 123 45 63";
        $user->phone_private = "031 890 12 30";
        $user->phone_mobile = "079 567 89 09";
        $user->email = "damian.schmid@bluewin.ch";
        $user->birthdate = "09.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Andrea";
        $spouse->last_name = "Schmid";
        $spouse->street = "Länggasse 3";
        $spouse->zip = "3000";
        $spouse->location = "Bern";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 63";
        $spouse->phone_private = "031 890 12 30";
        $spouse->phone_mobile = "079 567 89 00";
        $spouse->email = "andrea.schmid@bluewin.ch";
        $spouse->birthdate = "10.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Herr & Frau',
            'Adresszeile 2' => 'Damian & Andrea Schmid',
            'Adresszeile 3' => 'Länggasse 3',
            'Adresszeile 4' => '3000 Bern',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //personal with spouse not same gender (female main address), same last name
        $user = new UserMock();
        $user->gender = "Frau";
        $user->first_name = "Anette";
        $user->last_name = "Nanzer";
        $user->street = "Postgasse 34";
        $user->zip = "3600";
        $user->location = "Thun";
        $user->main_address = true;
        $user->phone_private = "031 123 45 64";
        $user->phone_private = "031 890 12 31";
        $user->phone_mobile = "079 567 89 01";
        $user->email = "anette.nanzer@bluewin.ch";
        $user->birthdate = "11.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Paul";
        $spouse->last_name = "Nanzer";
        $spouse->street = "Postgasse 34";
        $spouse->zip = "3600";
        $spouse->location = "Thun";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 64";
        $spouse->phone_private = "031 890 12 31";
        $spouse->phone_mobile = "079 567 89 02";
        $spouse->email = "paul.nanzer@bluewin.ch";
        $spouse->birthdate = "12.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Frau & Herr',
            'Adresszeile 2' => 'Anette & Paul Nanzer',
            'Adresszeile 3' => 'Postgasse 34',
            'Adresszeile 4' => '3600 Thun',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //personal with spouse not same gender (female main address), not same last name
        $user = new UserMock();
        $user->gender = "Frau";
        $user->first_name = "Eva";
        $user->last_name = "Bieri";
        $user->street = "Riedernrain 162";
        $user->zip = "3027";
        $user->location = "Bern";
        $user->main_address = true;
        $user->phone_private = "031 123 45 65";
        $user->phone_private = "031 890 12 32";
        $user->phone_mobile = "079 567 89 03";
        $user->email = "eva.bieri@bluewin.ch";
        $user->birthdate = "13.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Jürg";
        $spouse->last_name = "Fuhrer";
        $spouse->street = "Riedernrain 162";
        $spouse->zip = "3027";
        $spouse->location = "Bern";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 65";
        $spouse->phone_private = "031 890 12 32";
        $spouse->phone_mobile = "079 567 89 04";
        $spouse->email = "juerg.fuhrer@bluewin.ch";
        $spouse->birthdate = "14.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Frau Eva Bieri',
            'Adresszeile 2' => 'Herr Jürg Fuhrer',
            'Adresszeile 3' => 'Riedernrain 162',
            'Adresszeile 4' => '3027 Bern',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];

        //personal with spouse not same gender (male main address), not same last name
        $user = new UserMock();
        $user->gender = "Herr";
        $user->first_name = "Alfred";
        $user->last_name = "Hunziker";
        $user->street = "Attinghausenstrasse 20";
        $user->zip = "3014";
        $user->location = "Bern";
        $user->main_address = true;
        $user->phone_private = "031 123 45 66";
        $user->phone_private = "031 890 12 33";
        $user->phone_mobile = "079 567 89 05";
        $user->email = "alfred.hunziker@bluewin.ch";
        $user->birthdate = "15.02.1983";

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Stephanie";
        $spouse->last_name = "Widmer";
        $spouse->street = "Attinghausenstrasse 20";
        $spouse->zip = "3014";
        $spouse->location = "Bern";
        $spouse->main_address = false;
        $spouse->phone_private = "031 123 45 66";
        $spouse->phone_private = "031 890 12 33";
        $spouse->phone_mobile = "079 567 89 06";
        $spouse->email = "stephanie.widmer@bluewin.ch";
        $spouse->birthdate = "16.02.1983";

        $user->spouse = $spouse;

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Herr Alfred Hunziker',
            'Adresszeile 2' => 'Frau Stephanie Widmer',
            'Adresszeile 3' => 'Attinghausenstrasse 20',
            'Adresszeile 4' => '3014 Bern',
            'Adresszeile 5' => '',
            'Adresszeile 6' => '',
        ];
    }

    static function seedContributions(array &$users, array &$expected)
    {
        static::seedAddresses($users, $expected);
        // we are not interessted in the company entries here so we remove them
        array_shift($users);
        array_shift($users);
        array_shift($expected);
        array_shift($expected);

        // the first male single user will be "Aktivmitglied"
        $users[0]->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        $expected[0]['Beitragstyp'] = 'Mitgliederbeitrag BCB';
        $expected[0]['Betrag'] = '50.00';

        // the second femal single user wil be "Aktivmitlglieder Jugend"
        $users[1]->address_role = new RoleMock('bcb_aktivmitglied_jugend', 'Aktivmitglied Jugend');
        $expected[1]['Beitragstyp'] = 'Mitgliederbeitrag Jugend';
        $expected[1]['Betrag'] = '20.00';

        // the first "Ehepaar" will both be "Aktivmitglieder"
        $users[2]->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        $users[2]->spouse->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        $expected[2]['Beitragstyp'] = 'Mitgliederbeitrag Ehepaar';
        $expected[2]['Betrag'] = '80.00';

        // in the second "Ehepaar" the main address will be "Freimitglied", the spouse "Aktivmitglied" which means that
        // both don't have to pay and should be not in the final dataset.
        $users[3]->address_role = new RoleMock('bcb_freimitglied', 'Freimitglied');
        $users[3]->spouse->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        unset($expected[3]);

        // in the third "Ehepaar" the main address will be "Ehrenmitglied", the spouse "Aktivmitglied" which means that
        // both don't have to pay and should be not in the final dataset.
        $users[4]->address_role = new RoleMock('bcb_ehrenmitglied', 'Ehrenmitglied');
        $users[4]->spouse->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        unset($expected[4]);

        // in the fourth "Ehepaar" the main address will have a functionary role, the spouse "Aktivmitglied" which means
        // that both don't have to pay and should be not in the final dataset.
        $users[5]->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        $users[5]->hasFunctionaryRole = true;
        $users[5]->spouse->address_role = new RoleMock('bcb_aktivmitglied', 'Aktivmitglied');
        unset($expected[5]);


        // we don't need the further test users so lets remove them
        unset($users[6]);
        unset($expected[6]);
        unset($users[7]);
        unset($expected[7]);
    }

    static function seedShipping(array &$users, array &$expected)
    {
        static::seedAddresses($users, $expected);

        //the first user will not be marked to receive the program, so we remove him from expected
        array_shift($expected);

        //we mark every other user to receive the program
        for ($i = 1; $i < count($users); $i++) {
            $users[$i]->raw_program_shipment = true;
        }
    }
}