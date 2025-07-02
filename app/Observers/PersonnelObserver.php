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
        // This code checks if either the salary grade or step increment has changed
        // If so, it updates the salary_changed_at timestamp to the current time and saves the personnel record
        // This helps track when salary changes occur for the personnel
        if ($personnel->isDirty(['salary_grade_id', 'step_increment'])) {
            $personnel->salary_changed_at = now();
            $personnel->save();
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
