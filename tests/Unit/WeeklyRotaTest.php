<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RotaSlotStaff;
use App\Lib\WeeklyRota;

class WeeklyRotaTest extends TestCase
{
    /**
     * Test for obtaining the weekly rota data formatted to show in a table
     *
     * @return void
     */
    public function testGetWeeklyRotaTable()
    { 
        //Day 0, Hours: 12, Minutes: 600 
        $staff01 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 0,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '20:00:00',
            'endtime' => '3:00:00'
        ]);
        $staff02 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 0,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '16:00:00',
            'endtime' => '21:00:00'
        ]);
        $staff03 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 0,
            'staffid' => 3,
            'slottype' => 'dayoff',
        ]);

        //Day 1, Hours: 14, Minutes: 120 
        $staff11 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '15:00:00',
            'endtime' => '23:00:00'
        ]);
        $staff12 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 2,
            'slottype' => 'dayoff',
        ]);
        $staff13 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '16:00:00',
            'endtime' => '22:00:00'
        ]);

        //Day 2, Hours: 12, Minutes: 720 
        $staff21 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '15:00:00',
            'endtime' => '21:00:00'
        ]);
        $staff22 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 2,
            'slottype' => 'dayoff',
        ]);
        $staff23 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '21:00:00',
            'endtime' => '3:00:00'
        ]);

        //Day 3, Hours: 10, Minutes: 600 
        $staff31 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 3,
            'staffid' => 1,
            'slottype' => 'dayoff',
        ]);
        $staff32 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 3,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '16:00:00',
            'endtime' => '2:00:00'
        ]);
        $staff33 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 3,
            'staffid' => 3,
            'slottype' => 'dayoff',
        ]);

        //Day 4, Hours: 21, Minutes: 540 
        $staff41 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 4,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '20:00:00',
            'endtime' => '3:00:00'
        ]);
        $staff42 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 4,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '12:00:00',
            'endtime' => '19:00:00'
        ]);
        $staff43 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 4,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '15:00:00',
            'endtime' => '22:00:00'
        ]);

        //Day 5, Hours: 20, Minutes: 0 
        $staff51 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 5,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '14:00:00',
            'endtime' => '00:00:00'
        ]);
        $staff52 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 5,
            'staffid' => 2,
            'slottype' => 'dayoff',
        ]);
        $staff53 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 5,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '14:00:00',
            'endtime' => '00:00:00'
        ]);

        //Day 6, Hours: 13, Minutes: 180 
        $staff61 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 6,
            'staffid' => 1,
            'slottype' => 'dayoff',
        ]);
        $staff62 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 6,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '17:00:00',
            'endtime' => '23:00:00'
        ]);
        $staff63 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 6,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '18:00:00',
            'endtime' => '1:00:00'
        ]);

        $expectedTable = [
            'staff' => [
                1 => [
                    0 => [
                        'slottype' => 'shift',
                        'starttime' => '20:00:00',
                        'endtime' => '3:00:00'
                    ],
                    1 => [
                        'slottype' => 'shift',
                        'starttime' => '15:00:00',
                        'endtime' => '23:00:00'
                    ],
                    2 => [ 
                        'slottype' => 'shift',
                        'starttime' => '15:00:00',
                        'endtime' => '21:00:00'
                    ],
                    3 => [                   
                        'slottype' => 'dayoff',
                    ],
                    4 => [ 
                        'slottype' => 'shift', 
                        'starttime' => '20:00:00',
                        'endtime' => '3:00:00'
                    ],
                    5 => [ 
                        'slottype' => 'shift',
                        'starttime' => '14:00:00',
                        'endtime' => '00:00:00'
                    ],
                    6 => [ 
                        'slottype' => 'dayoff',
                    ],
                ],
                2 => [
                    0 => [
                        'slottype' => 'shift',
                        'starttime' => '16:00:00',
                        'endtime' => '21:00:00'
                    ],
                    1 => [
                        'slottype' => 'dayoff',
                    ],
                    2 => [       
                        'slottype' => 'dayoff',
                    ],
                    3 => [ 
                        'slottype' => 'shift',
                        'starttime' => '16:00:00',
                        'endtime' => '2:00:00'
                    ],
                    4 => [
                        'slottype' => 'shift',
                        'starttime' => '12:00:00',
                        'endtime' => '19:00:00'
                    ],
                    5 => [
                        'slottype' => 'dayoff',
                    ],
                    6 => [
                        'slottype' => 'shift',
                        'starttime' => '17:00:00',
                        'endtime' => '23:00:00'
                    ],
                ],
                3 => [
                    0 => [
                        'slottype' => 'dayoff',
                    ],
                    1 => [
                        'slottype' => 'shift',
                        'starttime' => '16:00:00',
                        'endtime' => '22:00:00'
                    ],
                    2 => [                    
                        'slottype' => 'shift',
                        'starttime' => '21:00:00',
                        'endtime' => '3:00:00'
                    ],
                    3 => [                    
                        'slottype' => 'dayoff',
                    ],
                    4 => [                    
                        'slottype' => 'shift',
                        'starttime' => '15:00:00',
                        'endtime' => '22:00:00'
                    ],
                    5 => [                    
                        'slottype' => 'shift',
                        'starttime' => '14:00:00',
                        'endtime' => '00:00:00'
                    ],
                    6 => [                    
                        'slottype' => 'shift',
                        'starttime' => '18:00:00',
                        'endtime' => '1:00:00'
                    ],
                ],
            ],
            'days' => [
                0 => [
                    'working_hours' => 12,
                    'premium_minutes' => 600,
                ],
                1 => [
                    'working_hours' => 14,
                    'premium_minutes' => 120,
                ],
                2 => [
                    'working_hours' => 12,
                    'premium_minutes' => 720,
                ],
                3 => [
                    'working_hours' => 10,
                    'premium_minutes' => 600,
                ],
                4 => [
                    'working_hours' => 21,
                    'premium_minutes' => 540,
                ],
                5 => [
                    'working_hours' => 20,
                    'premium_minutes' => 0,
                ],
                6 => [
                    'working_hours' => 13,
                    'premium_minutes' => 180,
                ],
            ]
        ];

        $rota = new WeeklyRota(332);
        $this->assertEquals($expectedTable, $rota->getTable());
    }
}
