<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RotaSlotStaff extends Model
{
    protected $table = 'rota_slot_staff';

    public function setCreatedAtAttribute($value)
    {
        // to Disable created_at
    }

    public function setUpdatedAtAttribute($value)
    {
        // to Disable updated_at
    }

    public function getMembersWorkingInDay($rotaid, $daynumber)
    {
        return $this->where('rotaid', $rotaid)
            ->where('daynumber', $daynumber)
            ->where('slottype', 'shift')
            ->whereNotNull('staffid')
            ->orderBy('starttime')
            ->get();        
    }

    public function getStaffMemberWorkingHours()
    {
        $starttime = Carbon::parse($this->starttime);
        $endtime = Carbon::parse($this->endtime);
        if ($starttime->gt($endtime)) {
            //End time is after 00:00
            $endtime->addDays(1);
        }
        return $endtime->diffInSeconds($starttime) / 3600;
    }

    public function getTotalWorkingHours($staffMembers)
    {
        $totalHours = 0;
        foreach ($staffMembers as $member)
        {
            $totalHours += $member->getStaffMemberWorkingHours();
        }
        return $totalHours;
    }

    public function getPremiumMinutes($staffMembers)
    {
        //Sorted timestamp array with staff start and end times
        $timestamps = $this->sortedTimestampArray($staffMembers);

        //Array with number of working members in each time period
        $staffWorkingPeriods = $this->workingPeriodsArray($timestamps);

        return $this->calculatePremiumMinutesFromPeriods($staffWorkingPeriods);
    }

    protected function sortedTimestampArray(&$staffMembers)
    {
        $timestamps = [];
        foreach ($staffMembers as $member)
        {
            $starttime = Carbon::parse($member->starttime);
            $endtime = Carbon::parse($member->endtime);
            if ($starttime->gt($endtime)) {
                //End time is after 00:00
                $endtime->addDays(1);
            }

            if (!isset($timestamps[$starttime->timestamp])) {
                $timestamps[$starttime->timestamp] = [];
            }
            $timestamps[$starttime->timestamp][$member->staffid] = 'start';

            if (!isset($timestamps[$endtime->timestamp])) {
                $timestamps[$endtime->timestamp] = [];
            }
            $timestamps[$endtime->timestamp][$member->staffid] = 'end';
        }
        ksort($timestamps);
        return $timestamps;
    }

    protected function workingPeriodsArray(&$timestamps)
    {
        $staffWorking = 0;
        $periods = [];
        $prevTimestamp = null;
        foreach ($timestamps as $timestamp => $staffShifts)
        {
            if (is_null($prevTimestamp)) {
                //First period
                $prevTimestamp = $timestamp;
            } else {
                $periods[] = [
                    'start' => $prevTimestamp,
                    'end' => $timestamp,
                    'working' => $staffWorking
                ]; 
                $prevTimestamp = $timestamp;
            }

            //Recalculate people working at this period
            foreach ($staffShifts as $shift)
            {
                if ($shift == 'start') {
                    $staffWorking++;
                } elseif ($shift == 'end') {
                    $staffWorking--;
                }
            }
        }
        return $periods;
    }

    protected function calculatePremiumMinutesFromPeriods(&$periods)
    {
        $premiumMinutes = 0;
        foreach ($periods as $period)
        {
            extract($period);
            if ($working == 1)
            {
                $premiumMinutes += (int) round(($end - $start) / 60);
            }
        }
        return $premiumMinutes;
    }
}
