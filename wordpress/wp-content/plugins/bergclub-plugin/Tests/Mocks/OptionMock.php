<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 18:03
 */

namespace BergclubPlugin\Tests\Mocks;


class OptionMock
{
    public static function get($key)
    {
        if ($key == 'mitgliederbeitraege') {
            return [
                'bcb' => [
                    'name' => 'Mitgliederbeitrag BCB',
                    'amount' => 50,
                ],
                'jugend' => [
                    'name' => 'Mitgliederbeitrag Jugend',
                    'amount' => 20,
                ],
                'ehepaar' => [
                    'name' => 'Mitgliederbeitrag Ehepaar',
                    'amount' => 80,
                ],
            ];
        } elseif ($key == 'tourenarten') {
            return [
                'bcb_bergtour' => 'Bergtour',
                'bcb_skitour' => 'Skitour',
                'bcb_langlauf' => 'Langlauf',
                'bcb_klettertraining' => 'Klettertraining',
                'bcb_velotour' => 'Velotour',
                'bcb_hochtour' => 'Hochtour',
                'bcb_pistenfahren' => 'Pistenfahren',
                'bcb_schneeschuhwanderung' => 'Schneeschuhw.',
                'bcb_klettertour' => 'Klettertour',
                'bcb_diverses' => 'Diverses',
                'bcb_wanderung' => 'Wanderung',
                'bcb_klettersteig' => 'Klettersteig',
                'bcb_hoehlentour' => 'Höhlentour',
                'bcb_abendwanderung' => 'Abendwanderung',
            ];
        }

        return null;
    }
}