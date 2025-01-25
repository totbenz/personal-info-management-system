<?php

namespace App\Observers;

use App\Models\Personnel;
use App\Models\ServiceRecord;

class PersonnelObserver
{
    /**
     * Handle the Personnel "created" event.
     */
    public function created(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "updated" event.
     */
    public function updated(Personnel $personnel)
    {
        // Check if specific fields are dirty (have been changed)
        if ($personnel->isDirty(['position_id', 'appointment', 'salary_grade', 'school_id'])) {
            ServiceRecord::create([
                'personnel_id' => $personnel->id,
                'from_date' => now(), // Adjust this as needed
                'to_date' => null,    // Adjust this as needed
                'designation' => $personnel->position->title,
                'appointment_status' => $personnel->appointment,
                'salary' => $personnel->salary_grade,
                'station' => $personnel->school->school_name, // Adjust this as needed
                'branch' => $personnel->school->district->title,   // Adjust this as needed
                'lv_wo_pay' => $personnel->lv_wo_pay, // Adjust this as needed
                'separation_date_cause' => $personnel->separation_date_cause // Adjust this as needed
            ]);
        }
    }

    /**
     * Handle the Personnel "deleted" event.
     */
    public function deleted(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "restored" event.
     */
    public function restored(Personnel $personnel): void
    {
        //
    }

    /**
     * Handle the Personnel "force deleted" event.
     */
    public function forceDeleted(Personnel $personnel): void
    {
        //
    }
}
