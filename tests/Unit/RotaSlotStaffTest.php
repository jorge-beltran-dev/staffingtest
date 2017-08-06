<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\RotaSlotStaff;

class RotaSlotStaffTest extends TestCase
{
    /**
     * Test get staff members working on a day
     *
     * @return void
     */
    public function testStaffMembersByDay()
    {
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
        ]);
        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 2,
            'slottype' => 'shift',
        ]);
        $staff3 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 3,
            'slottype' => 'shift',
        ]);
        $staff4 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 4,
            'slottype' => 'dayoff',
        ]);
        $staff5 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 3,
            'staffid' => 5,
            'slottype' => 'dayoff',
        ]);
        $staff6 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 6,
            'slottype' => 'shift',
        ]);

        $rotaSlotStaff = new RotaSlotStaff;
        $staffSlots = $rotaSlotStaff->getMembersWorkingInDay(332, 1);

        $this->assertEquals(3, $staffSlots->count());
        $this->assertContains($staff1->id,$staffSlots->pluck('id')->toArray());
        $this->assertContains($staff3->id,$staffSlots->pluck('id')->toArray());
        $this->assertContains($staff6->id,$staffSlots->pluck('id')->toArray());
    }

    /**
     * Test slots with null staff id are ignored
     *
     * @return void
     */
    public function testNullStaffIdIsIgnored()
    {
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
        ]);
        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 2,
            'slottype' => 'dayoff',
        ]);
        $staff3 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => null,
            'slottype' => 'shift',
        ]);
        $staff4 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 4,
            'slottype' => 'shift',
        ]);

        $rotaSlotStaff = new RotaSlotStaff;
        $staffSlots = $rotaSlotStaff->getMembersWorkingInDay(332, 1);

        $this->assertEquals(2, $staffSlots->count());
        $this->assertContains($staff1->id,$staffSlots->pluck('id')->toArray());
        $this->assertContains($staff4->id,$staffSlots->pluck('id')->toArray());
    }

    /**
     * Test function for obtaining member working hours in a day
     *
     * @return void
     */
    public function testgetStaffMemberWorkingHours()
    {
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '12:00:00',
            'endtime' => '20:00:00'
        ]);
        $this->assertEquals(8, $staff1->getStaffMemberWorkingHours());

        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '19:00:00',
            'endtime' => '2:00:00'
        ]);
        $this->assertEquals(7, $staff2->getStaffMemberWorkingHours());
    }

    /**
     * Test total working hours on a day
     *
     * @return void
     */
    public function testGetTotalWorkingHoursOnADay()
    {
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '12:00:00',
            'endtime' => '20:00:00'
        ]);
        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '15:00:00',
            'endtime' => '23:00:00'
        ]);
        $staff3 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '19:00:00',
            'endtime' => '3:00:00'
        ]);
        $staff4 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 4,
            'slottype' => 'dayoff',
            'starttime' => '12:00:00',
            'endtime' => '20:00:00'
        ]);
        $staff5 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 3,
            'staffid' => 5,
            'slottype' => 'dayoff',
            'starttime' => '16:00:00',
            'endtime' => '00:00:00'
        ]);
        $staff6 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 6,
            'slottype' => 'shift',
            'starttime' => '21:00:00',
            'endtime' => '2:00:00'
        ]);

        $rotaSlotStaff = new RotaSlotStaff;
        $totalWorkingHours = $rotaSlotStaff->getTotalWorkingHours($rotaSlotStaff->getMembersWorkingInDay(332, 1));
        $this->assertEquals(21, $totalWorkingHours);
    }

    /**
     * Test getting premium minutes for a given day
     *
     * @return void
     */
    public function testGetPremiumMinutes()
    {
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '12:00:00',
            'endtime' => '20:00:00'
        ]);
        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '15:45:00',
            'endtime' => '23:00:00'
        ]);
        $staff3 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '19:00:00',
            'endtime' => '3:00:00'
        ]);
        $staff4 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 1,
            'staffid' => 4,
            'slottype' => 'shift',
            'starttime' => '21:00:00',
            'endtime' => '2:00:00'
        ]);

        $rotaSlotStaff = new RotaSlotStaff;
        $premiumMinutes = $rotaSlotStaff->getPremiumMinutes($rotaSlotStaff->getMembersWorkingInDay(332, 1));
        $this->assertEquals(285, $premiumMinutes);

        //Additional assert with different time values
        $staff1 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 1,
            'slottype' => 'shift',
            'starttime' => '12:00:00',
            'endtime' => '19:00:00'
        ]);
        $staff2 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 2,
            'slottype' => 'shift',
            'starttime' => '14:30:00',
            'endtime' => '23:00:00'
        ]);
        $staff3 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 3,
            'slottype' => 'shift',
            'starttime' => '19:00:00',
            'endtime' => '23:00:00'
        ]);
        $staff4 = factory(\App\RotaSlotStaff::class)->create([
            'daynumber' => 2,
            'staffid' => 4,
            'slottype' => 'shift',
            'starttime' => '23:00:00',
            'endtime' => '3:00:00'
        ]);

        $premiumMinutes = $rotaSlotStaff->getPremiumMinutes($rotaSlotStaff->getMembersWorkingInDay(332, 2));
        $this->assertEquals(390, $premiumMinutes);
    }
}
