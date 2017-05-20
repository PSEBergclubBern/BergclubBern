<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 13:24
 */

namespace BergclubPlugin\Tests\Export\Data;


class UserDataSeeder
{
    static function seedShipping(array &$users, array &$expected){
        static::seedAddresses($users, $expected);

        //the first user will not be marked to receive the program, so we remove him from expected
        array_shift($expected);

        //we mark every other user to receive the program
        for($i = 1; $i < count($users); $i++){
            $users[$i]->raw_program_shipment = true;
        }
    }
    static function seedAddresses(array &$users, array &$expected){
        $users = [];
        $expected = [];

        //company with no personal contact
        $user = new UserMock();
        $user->company = "Test AG";
        $user->address_addition = "Postfach";
        $user->street = "Teststrasse 1";
        $user->zip = "1234";
        $user->location = "Testlingen";

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

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Müller AG',
            'Adresszeile 2' => 'Frau Sabine Müller',
            'Adresszeile 3' => 'Postfach 397',
            'Adresszeile 4' => 'Weidenweg 1',
            'Adresszeile 5' => '4613 Rickenbach (SO)',
            'Adresszeile 6' => '',
        ];


        //personal without spouse
        $user = new UserMock();
        $user->gender = "Herr";
        $user->first_name = "Fritz";
        $user->last_name = "Muster";
        $user->street = "Musterstrasse 1";
        $user->zip = "9999";
        $user->location = "Musterlingen";

        $users[] = $user;

        $expected[] = [
            'Adresszeile 1' => 'Herr',
            'Adresszeile 2' => 'Fritz Muster',
            'Adresszeile 3' => 'Musterstrasse 1',
            'Adresszeile 4' => '9999 Musterlingen',
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

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Konrad";
        $spouse->last_name = "Wolfisberger";
        $spouse->main_address = false;

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

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Brigitte";
        $spouse->last_name = "Guggisberger";
        $spouse->main_address = false;

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

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Andrea";
        $spouse->last_name = "Schmid";
        $spouse->main_address = false;

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

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Paul";
        $spouse->last_name = "Nanzer";
        $spouse->main_address = false;

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

        $spouse = new UserMock();
        $spouse->gender = "Herr";
        $spouse->first_name = "Jürg";
        $spouse->last_name = "Fuhrer";
        $spouse->main_address = false;

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

        $spouse = new UserMock();
        $spouse->gender = "Frau";
        $spouse->first_name = "Stephanie";
        $spouse->last_name = "Widmer";
        $spouse->main_address = false;

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
}