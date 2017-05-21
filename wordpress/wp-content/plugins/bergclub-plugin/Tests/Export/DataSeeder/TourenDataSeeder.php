<?php
/**
 * Created by PhpStorm.
 * User: mathi
 * Date: 20.05.2017
 * Time: 18:38
 */

namespace BergclubPlugin\Tests\Export\DataSeeder;


class TourenDataSeeder
{
    public static function seedCalendar(&$mockedPostData, &$expectedResult)
    {
        $mockedPostData = static::getTourenData();

        $expectedResult = [
            '2017-05-20' => [
                'Tour1 (Typ1, T1)',
                'TourJ1 (Jugend, TypJ1, TJ1)',
            ],
            '2017-05-21' => [
                'Tour2 (Typ2)'
            ],
            '2017-05-22' => [
                'Tour3'
            ],
            '2017-05-23' => [
                'Tour4 (Typ4, T4, bis 25.05.)',
            ],
            '2017-05-26' => [
                'TourB1 (TypB1, TB1)',
            ],
        ];
    }

    private static function getTourenData()
    {
        $result = [];

        // one-day tour, BCB
        $result[] = [
            'post_status' => 'publish',
            'post_date' => '2017-01-01 12:00:00',
            'post_modified' => '2017-02-01 12:00:00',
            'title' => 'Tour1',
            'leader' => 'Leader1',
            'coLeader' => 'CoLeader1',
            'training' => 0,
            'jsEvent' => 0,
            'dateFrom' => '20.05.2017',
            'dateTo' => '20.05.2017',
            'dateDisplayShort' => '20.5.',
            'type' => 'Typ1',
            'requirementsTechnical' => 'T1',
            'requirementsConditional' => 'leicht',
            'riseUpMeters' => '100 m up',
            'riseDownMeters' => '100 m down',
            'duration' => '1:00',
            'meetpoint' => 'Meetpoint1',
            'meetingPointTime' => '7:00',
            'returnBack' => '17:00',
            'costs' => '20.00',
            'costsFor' => 'Reason1',
            'isYouthRaw' => 0,
            'isSeveralDays' => false,
            'signupUntil' => '19.05.2017',
            'signUpToNoLinks' => 'SignupToNoLinks1',
        ];

        // one-day tour, no technical requirements, no "signup to" BCB with additional info, equipment, food, map material
        $result[] = [
            'leader' => 'Leader2',
            'training' => 0,
            'jsEvent' => 0,
            'post_status' => 'future',
            'post_date' => '2017-01-02 12:00:00',
            'post_modified' => '2017-02-02 12:00:00',
            'title' => 'Tour2',
            'dateFrom' => '21.05.2017',
            'dateTo' => '21.05.2017',
            'dateDisplayShort' => '21.5.',
            'type' => 'Typ2',
            'requirementsConditional' => 'mittel',
            'riseUpMeters' => '200 m up',
            'riseDownMeters' => '200 m down',
            'duration' => '2:00',
            'meetpoint' => 'Meetpoint2',
            'meetingPointTime' => '7:15',
            'returnBack' => '17:15',
            'costs' => '30.00',
            'costsFor' => 'Reason2',
            'isYouthRaw' => 0,
            'isSeveralDays' => false,
            'signupUntil' => '20.05.2017',
            'additionalInfo' => 'AdditionalInfo1',
            'equipment' => 'Equipment1',
            'food' => 'Food1',
            'mapMaterial' => 'MapMaterial1',
        ];

        // one-day tour, no technical requirements, no type, BCB
        $result[] = [
            'leader' => 'Leader3',
            'coLeader' => 'CoLeader3',
            'training' => 0,
            'jsEvent' => 0,
            'post_status' => 'draft',
            'post_date' => '2017-01-03 12:00:00',
            'post_modified' => '2017-02-03 12:00:00',
            'title' => 'Tour3',
            'dateFrom' => '22.05.2017',
            'dateTo' => '22.05.2017',
            'dateDisplayShort' => '22.5.',
            'riseUpMeters' => '300 m up',
            'riseDownMeters' => '300 m down',
            'duration' => '3:00',
            'meetpoint' => 'Meetpoint3',
            'meetingPointTime' => '7:30',
            'returnBack' => '17:30',
            'costs' => '40.00',
            'costsFor' => 'Reason3',
            'isYouthRaw' => 0,
            'isSeveralDays' => false,
            'signupUntil' => '21.05.2017',
            'signUpToNoLinks' => 'SignupToNoLinks3',
        ];

        // multiple-day tour, BCB
        $result[] = [
            'leader' => 'Leader4',
            'training' => 0,
            'jsEvent' => 0,
            'post_status' => 'pending',
            'post_date' => '2017-01-04 12:00:00',
            'post_modified' => '2017-02-04 12:00:00',
            'title' => 'Tour4',
            'dateFrom' => '23.05.2017',
            'dateTo' => '25.05.2017',
            'dateDisplayShort' => '23. - 24.5.',
            'type' => 'Typ4',
            'requirementsTechnical' => 'T4',
            'requirementsConditional' => 'schwer',
            'riseUpMeters' => '400 m up',
            'riseDownMeters' => '400 m down',
            'duration' => '4:00',
            'meetpoint' => 'Meetpoint4',
            'meetingPointTime' => '7:45',
            'returnBack' => '17:45',
            'costs' => '50.00',
            'costsFor' => 'Reason4',
            'isYouthRaw' => 0,
            'isSeveralDays' => true,
            'signupUntil' => '22.05.2017',
            'signUpToNoLinks' => 'SignupToNoLinks4',
        ];

        // one-day tour, Jugend, same day as first BCB tour
        $result[] = [
            'leader' => 'Leader5',
            'coLeader' => 'CoLeader5',
            'training' => 0,
            'jsEvent' => 1,
            'post_status' => 'publish',
            'post_date' => '2017-01-05 12:00:00',
            'post_modified' => '2017-02-05 12:00:00',
            'title' => 'TourJ1',
            'dateFrom' => '20.05.2017',
            'dateTo' => '20.05.2017',
            'dateDisplayShort' => '20.5.',
            'type' => 'TypJ1',
            'requirementsTechnical' => 'TJ1',
            'requirementsConditional' => 'leicht',
            'riseUpMeters' => '500 m up',
            'riseDownMeters' => '500 m down',
            'duration' => '5:00',
            'meetpoint' => 'Meetpoint5',
            'meetingPointTime' => '8:00',
            'returnBack' => '18:00',
            'costs' => '60.00',
            'costsFor' => 'Reason5',
            'isYouthRaw' => 1,
            'isSeveralDays' => false,
            'signupUntil' => '18.05.2017',
            'signUpToNoLinks' => 'SignupToNoLinksJ1',
        ];

        // one-day tour, BCB + Jugend, same day as first BCB tour, no duration, no signup deadline
        $result[] = [
            'leader' => 'Leader6',
            'coLeader' => 'CoLeader6',
            'training' => 1,
            'jsEvent' => 0,
            'post_status' => 'future',
            'post_date' => '2017-01-06 12:00:00',
            'post_modified' => '2017-02-06 12:00:00',
            'title' => 'TourB1',
            'dateFrom' => '26.05.2017',
            'dateTo' => '26.05.2017',
            'dateDisplayShort' => '26.5.',
            'type' => 'TypB1',
            'requirementsTechnical' => 'TB1',
            'requirementsConditional' => 'mittel',
            'riseUpMeters' => '600 m up',
            'riseDownMeters' => '600 m down',
            'meetpoint' => 'Meetpoint6',
            'meetingPointTime' => '8:15',
            'returnBack' => '18:15',
            'costs' => '70.00',
            'costsFor' => 'Reason6',
            'isYouthRaw' => 2,
            'isSeveralDays' => false,
            'signUpToNoLinks' => 'SignupToNoLinksB1',
        ];

        return $result;
    }

    public static function seedPfarrblatt(&$mockedPostData, &$expectedResult)
    {
        $mockedPostData = static::getTourenData();

        $expectedResult = [
            'Samstag, 20. Mai: Typ1, Tour1, Anmeldung an: SignupToNoLinks1',
            'Sonntag, 21. Mai: Typ2, Tour2',
            'Montag, 22. Mai: , Tour3, Anmeldung an: SignupToNoLinks3',
            'Dienstag/Donnerstag, 23./25. Mai: Typ4, Tour4, Anmeldung an: SignupToNoLinks4',
            'Samstag, 20. Mai: TypJ1, TourJ1, Anmeldung an: SignupToNoLinksJ1',
            'Freitag, 26. Mai: TypB1, TourB1, Anmeldung an: SignupToNoLinksB1'
        ];
    }

    public static function seedProgram(&$mockedPostData, &$expectedResult)
    {
        $mockedPostData = static::getTourenData();

        $expectedResult = [
            'data' =>
                [
                    0 =>
                        [
                            'dateDisplayShort' => '20.5.',
                            'dateDisplayWeekday' => 'Sa',
                            'title' => 'Tour1',
                            'type' => 'Typ1',
                            'requirementsTechnical' => 'T1',
                            'requirementsConditional' => 'leicht',
                            'riseUpMeters' => '100 m up',
                            'riseDownMeters' => '100 m down',
                            'duration' => '1:00',
                            'meetpoint' => 'Meetpoint1',
                            'meetingPointTime' => '7:00',
                            'returnBack' => '17:00',
                            'costs' => '20.00',
                            'costsFor' => 'Reason1',
                            'signupUntil' => '19.05.2017',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                ],
                            'equipment' =>
                                [
                                ],
                            'food' => '',
                            'mapMaterial' => '',
                        ],
                    1 =>
                        [
                            'dateDisplayShort' => '21.5.',
                            'dateDisplayWeekday' => 'So',
                            'title' => 'Tour2',
                            'type' => 'Typ2',
                            'requirementsTechnical' => '',
                            'requirementsConditional' => 'mittel',
                            'riseUpMeters' => '200 m up',
                            'riseDownMeters' => '200 m down',
                            'duration' => '2:00',
                            'meetpoint' => 'Meetpoint2',
                            'meetingPointTime' => '7:15',
                            'returnBack' => '17:15',
                            'costs' => '30.00',
                            'costsFor' => 'Reason2',
                            'signupUntil' => '20.05.2017',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                    0 => 'AdditionalInfo1',
                                ],
                            'equipment' =>
                                [
                                    0 => 'Equipment1',
                                ],
                            'food' => 'Food1',
                            'mapMaterial' => 'MapMaterial1',
                        ],
                    2 =>
                        [
                            'dateDisplayShort' => '22.5.',
                            'dateDisplayWeekday' => 'Mo',
                            'title' => 'Tour3',
                            'type' => '',
                            'requirementsTechnical' => '',
                            'requirementsConditional' => '',
                            'riseUpMeters' => '300 m up',
                            'riseDownMeters' => '300 m down',
                            'duration' => '3:00',
                            'meetpoint' => 'Meetpoint3',
                            'meetingPointTime' => '7:30',
                            'returnBack' => '17:30',
                            'costs' => '40.00',
                            'costsFor' => 'Reason3',
                            'signupUntil' => '21.05.2017',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                ],
                            'equipment' =>
                                [
                                ],
                            'food' => '',
                            'mapMaterial' => '',
                        ],
                    3 =>
                        [
                            'dateDisplayShort' => '23. - 24.5.',
                            'dateDisplayWeekday' => 'Di - Do',
                            'title' => 'Tour4',
                            'type' => 'Typ4',
                            'requirementsTechnical' => 'T4',
                            'requirementsConditional' => 'schwer',
                            'riseUpMeters' => '400 m up',
                            'riseDownMeters' => '400 m down',
                            'duration' => '4:00',
                            'meetpoint' => 'Meetpoint4',
                            'meetingPointTime' => '7:45',
                            'returnBack' => '17:45',
                            'costs' => '50.00',
                            'costsFor' => 'Reason4',
                            'signupUntil' => '22.05.2017',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                ],
                            'equipment' =>
                                [
                                ],
                            'food' => '',
                            'mapMaterial' => '',
                        ],
                    4 =>
                        [
                            'dateDisplayShort' => '20.5.',
                            'dateDisplayWeekday' => 'Sa',
                            'title' => 'TourJ1',
                            'type' => 'TypJ1',
                            'requirementsTechnical' => 'TJ1',
                            'requirementsConditional' => 'leicht',
                            'riseUpMeters' => '500 m up',
                            'riseDownMeters' => '500 m down',
                            'duration' => '5:00',
                            'meetpoint' => 'Meetpoint5',
                            'meetingPointTime' => '8:00',
                            'returnBack' => '18:00',
                            'costs' => '60.00',
                            'costsFor' => 'Reason5',
                            'signupUntil' => '18.05.2017',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                ],
                            'equipment' =>
                                [
                                ],
                            'food' => '',
                            'mapMaterial' => '',
                        ],
                    5 =>
                        [
                            'dateDisplayShort' => '26.5.',
                            'dateDisplayWeekday' => 'Fr',
                            'title' => 'TourB1',
                            'type' => 'TypB1',
                            'requirementsTechnical' => 'TB1',
                            'requirementsConditional' => 'mittel',
                            'riseUpMeters' => '600 m up',
                            'riseDownMeters' => '600 m down',
                            'duration' => '',
                            'meetpoint' => 'Meetpoint6',
                            'meetingPointTime' => '8:15',
                            'returnBack' => '18:15',
                            'costs' => '70.00',
                            'costsFor' => 'Reason6',
                            'signupUntil' => '',
                            'signUpTo' => '',
                            'additionalInfo' =>
                                [
                                ],
                            'equipment' =>
                                [
                                ],
                            'food' => '',
                            'mapMaterial' => '',
                        ],
                ],
            'anmeldeTermine' =>
                [
                    '2017-05-18' =>
                        [
                            0 =>
                                [
                                    'signupUntil' => '18.05.',
                                    'info' => 'TourJ1 (TypJ1, 20.5.)',
                                ],
                        ],
                    '2017-05-19' =>
                        [
                            0 =>
                                [
                                    'signupUntil' => '19.05.',
                                    'info' => 'Tour1 (Typ1, 20.5.)',
                                ],
                        ],
                    '2017-05-20' =>
                        [
                            0 =>
                                [
                                    'signupUntil' => '20.05.',
                                    'info' => 'Tour2 (Typ2, 21.5.)',
                                ],
                        ],
                    '2017-05-21' =>
                        [
                            0 =>
                                [
                                    'signupUntil' => '21.05.',
                                    'info' => 'Tour3 (22.5.)',
                                ],
                        ],
                    '2017-05-22' =>
                        [
                            0 =>
                                [
                                    'signupUntil' => '22.05.',
                                    'info' => 'Tour4 (Typ4, 23. - 24.5.)',
                                ],
                        ],
                ],
        ];
    }

    public static function seedTouren(&$mockedPostData, &$expectedResult)
    {
        $mockedPostData = static::getTourenData();

        $expectedResult = [
            0 =>
                [
                    'Status' => 'Veröffentlicht',
                    'Erstellt am' => '01.01.2017',
                    'Geändert am' => '01.02.2017',
                    'Datum von' => '20.05.2017',
                    'Datum bis' => '20.05.2017',
                    'Titel' => 'Tour1',
                    'Leiter' => 'Leader1',
                    'Co-Leiter' => 'CoLeader1',
                    'Art' => 'Typ1',
                    'Schwierigkeit' => 'T1',
                    'Konditionell' => 'leicht',
                    'Training' => 0,
                    'J+S Event' => 0,
                    'Aufstieg' => '100 m up',
                    'Abstieg' => '100 m down',
                    'Dauer' => '1:00',
                    'Treffpunkt' => 'Meetpoint1',
                    'Zeit' => '7:00',
                    'Rückkehr' => '17:00',
                    'Kosten' => '20.00',
                    'Kosten für' => 'Reason1',
                    'Anmeldung bis' => '19.05.2017',
                    'Anmeldung an' => NULL,
                ],
            1 =>
                [
                    'Status' => 'Zukünftig (Publizierungsdatum in Zukunft)',
                    'Erstellt am' => '02.01.2017',
                    'Geändert am' => '02.02.2017',
                    'Datum von' => '21.05.2017',
                    'Datum bis' => '21.05.2017',
                    'Titel' => 'Tour2',
                    'Leiter' => 'Leader2',
                    'Co-Leiter' => NULL,
                    'Art' => 'Typ2',
                    'Schwierigkeit' => NULL,
                    'Konditionell' => 'mittel',
                    'Training' => 0,
                    'J+S Event' => 0,
                    'Aufstieg' => '200 m up',
                    'Abstieg' => '200 m down',
                    'Dauer' => '2:00',
                    'Treffpunkt' => 'Meetpoint2',
                    'Zeit' => '7:15',
                    'Rückkehr' => '17:15',
                    'Kosten' => '30.00',
                    'Kosten für' => 'Reason2',
                    'Anmeldung bis' => '20.05.2017',
                    'Anmeldung an' => NULL,
                ],
            2 =>
                [
                    'Status' => 'Entwurf',
                    'Erstellt am' => '03.01.2017',
                    'Geändert am' => '03.02.2017',
                    'Datum von' => '22.05.2017',
                    'Datum bis' => '22.05.2017',
                    'Titel' => 'Tour3',
                    'Leiter' => 'Leader3',
                    'Co-Leiter' => 'CoLeader3',
                    'Art' => NULL,
                    'Schwierigkeit' => NULL,
                    'Konditionell' => NULL,
                    'Training' => 0,
                    'J+S Event' => 0,
                    'Aufstieg' => '300 m up',
                    'Abstieg' => '300 m down',
                    'Dauer' => '3:00',
                    'Treffpunkt' => 'Meetpoint3',
                    'Zeit' => '7:30',
                    'Rückkehr' => '17:30',
                    'Kosten' => '40.00',
                    'Kosten für' => 'Reason3',
                    'Anmeldung bis' => '21.05.2017',
                    'Anmeldung an' => NULL,
                ],
            3 =>
                [
                    'Status' => 'Veröffentlichung beantragt',
                    'Erstellt am' => '04.01.2017',
                    'Geändert am' => '04.02.2017',
                    'Datum von' => '23.05.2017',
                    'Datum bis' => '25.05.2017',
                    'Titel' => 'Tour4',
                    'Leiter' => 'Leader4',
                    'Co-Leiter' => NULL,
                    'Art' => 'Typ4',
                    'Schwierigkeit' => 'T4',
                    'Konditionell' => 'schwer',
                    'Training' => 0,
                    'J+S Event' => 0,
                    'Aufstieg' => '400 m up',
                    'Abstieg' => '400 m down',
                    'Dauer' => '4:00',
                    'Treffpunkt' => 'Meetpoint4',
                    'Zeit' => '7:45',
                    'Rückkehr' => '17:45',
                    'Kosten' => '50.00',
                    'Kosten für' => 'Reason4',
                    'Anmeldung bis' => '22.05.2017',
                    'Anmeldung an' => NULL,
                ],
            4 =>
                [
                    'Status' => 'Veröffentlicht',
                    'Erstellt am' => '05.01.2017',
                    'Geändert am' => '05.02.2017',
                    'Datum von' => '20.05.2017',
                    'Datum bis' => '20.05.2017',
                    'Titel' => 'TourJ1',
                    'Leiter' => 'Leader5',
                    'Co-Leiter' => 'CoLeader5',
                    'Art' => 'TypJ1',
                    'Schwierigkeit' => 'TJ1',
                    'Konditionell' => 'leicht',
                    'Training' => 0,
                    'J+S Event' => 1,
                    'Aufstieg' => '500 m up',
                    'Abstieg' => '500 m down',
                    'Dauer' => '5:00',
                    'Treffpunkt' => 'Meetpoint5',
                    'Zeit' => '8:00',
                    'Rückkehr' => '18:00',
                    'Kosten' => '60.00',
                    'Kosten für' => 'Reason5',
                    'Anmeldung bis' => '18.05.2017',
                    'Anmeldung an' => NULL,
                ],
            5 =>
                [
                    'Status' => 'Zukünftig (Publizierungsdatum in Zukunft)',
                    'Erstellt am' => '06.01.2017',
                    'Geändert am' => '06.02.2017',
                    'Datum von' => '26.05.2017',
                    'Datum bis' => '26.05.2017',
                    'Titel' => 'TourB1',
                    'Leiter' => 'Leader6',
                    'Co-Leiter' => 'CoLeader6',
                    'Art' => 'TypB1',
                    'Schwierigkeit' => 'TB1',
                    'Konditionell' => 'mittel',
                    'Training' => 1,
                    'J+S Event' => 0,
                    'Aufstieg' => '600 m up',
                    'Abstieg' => '600 m down',
                    'Dauer' => NULL,
                    'Treffpunkt' => 'Meetpoint6',
                    'Zeit' => '8:15',
                    'Rückkehr' => '18:15',
                    'Kosten' => '70.00',
                    'Kosten für' => 'Reason6',
                    'Anmeldung bis' => NULL,
                    'Anmeldung an' => NULL,
                ],
        ];
    }
}