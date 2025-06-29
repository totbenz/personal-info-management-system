<?php

namespace App\Observers;

use App\Models\Personnel;
use App\Models\ServiceRecord;
use App\Models\SalaryChange;

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

    // public function updating(Personnel $personnel)
    // {
    //     // Only log if salary grade or step or salary is changing
    //     if (
    //         $personnel->isDirty('salary_grade_id') ||
    //         $personnel->isDirty('step_increment') ||
    //         $personnel->isDirty('salary')
    //     ) {
    //         SalaryChange::create([
    //             'personnel_id' => $personnel->id,
    //             'type' => 'NOSI', // or 'NOSA', set your logic here
    //             'previous_salary_grade' => $personnel->getOriginal('salary_grade_id'),
    //             'current_salary_grade' => $personnel->salary_grade_id,
    //             'previous_salary_step' => $personnel->getOriginal('step_increment'),
    //             'current_salary_step' => $personnel->step_increment,
    //             'previous_salary' => $personnel->getOriginal('salary'),
    //             'current_salary' => $personnel->salary,
    //             'actual_monthly_salary_as_of_date' => now(),
    //             'adjusted_monthly_salary_date' => now(),
    //         ]);
    //     }
    // }
}
