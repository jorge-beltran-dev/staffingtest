<?php

namespace App\Lib;

use App\RotaSlotStaff;

class WeeklyRota
{
    protected $rotaid;

    protected $rotaSlotStaff;

    protected $emtpyDaysLine = [];

    public function __construct($rotaid)
    {
        $this->rotaid = $rotaid;
        $this->rotaSlotStaff = new RotaSlotStaff;
        for ($i = 0; $i <= 6; $i++) {
            $this->emptyDaysLine[$i] = [];
        }
    }

    public function getTable()
    {
        //Initialize table;
        $table = [
            'staff' => [],
            'days' => $this->emptyDaysLine
        ];

        foreach ($table['days'] as $day => $dayData) 
        {
            $staffSlots = $this->rotaSlotStaff->getMembersWorkingInDay($this->rotaid, $day);
            $this->addStaffForDay($table, $day, $staffSlots);
            $this->addDayTotals($table, $day, $staffSlots);
        }

        return $table;
    }

    protected function addStaffForDay(&$table, $day, $staffSlots)
    {
        foreach ($staffSlots as $slot) {
            if (!isset($table['staff'][$slot->staffid]))
            {
                $table['staff'][$slot->staffid] = $this->initializeStaff($day, $slot);
            } 

            //Staff that is on switch
            $table['staff'][$slot->staffid][$day] = [
                'slottype' => $slot->slottype,
                'starttime' => $slot->starttime, 
                'endtime' => $slot->endtime, 
            ];

            //Staff that is on dayoff
            foreach ($table['staff'] as $staffid => $daysData) {
                if (!isset($table['staff'][$staffid][$day])) {
                    $table['staff'][$staffid][$day] = ['slottype' => 'dayoff'];
                }
            }
        }
    }

    protected function initializeStaff($day, &$slot)
    {
        if ($day == 0) {
            return [0 => []];
        } else {
            $staff = [];
            for ($i = 0; $i < $day; $i++)
            {
                $staff[$i] = ['slottype' => 'dayoff'];
            }
            return $staff;
        }
    }

    protected function addDayTotals(&$table, $day, $staffSlots)
    {
        $table['days'][$day] = [
            'working_hours' => $this->rotaSlotStaff->getTotalWorkingHours($staffSlots),
            'premium_minutes' => $this->rotaSlotStaff->getPremiumMinutes($staffSlots),
        ]; 
    }
}
