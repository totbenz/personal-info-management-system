<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\School;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    public function definition()
    {
        // Get a random position
        $position = Position::inRandomOrder()->first();
        $classification = $position ? $position->classification : null;

        // Determine allowed job_status based on classification
        if ($classification === 'teaching') {
            $jobStatusOptions = [
                'Sick leave',
                'Personal leave',
            ];
        } else {
            $jobStatusOptions = [
                'Active',
                'Vacation',
                'Terminated',
                'Suspended',
                'Resigned',
                'Probation',
                'Vacation leave',
                'Sick leave',
                'Compensatory time off',
                'Force leave',
                'Special privilege leave',
                'Personal leave',
                'Maternity leave',
                'Study leave',
                'Rehabilitation leave',
            ];
        }

        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->firstName,
            'last_name' => $this->faker->lastName,
            'name_ext' => $this->faker->optional()->suffix,
            'sex' => $this->faker->randomElement(['male', 'female']),
            'civil_status' => $this->faker->randomElement(['single', 'married', 'widowed', 'divorced', 'seperated', 'others']),
            'citizenship' => 'Filipino',
            'blood_type' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(50, 100),
            'date_of_birth' => $this->faker->date,
            'place_of_birth' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'tel_no' => $this->faker->phoneNumber,
            'mobile_no' => $this->faker->phoneNumber,
            'personnel_id' => $this->faker->unique()->numberBetween(1000000, 9999999),
            'school_id' => School::inRandomOrder()->first()->id,
            'position_id' => $position ? $position->id : null,
            'appointment' => $this->faker->randomElement(['regular', 'part-time', 'temporary', 'contract']),
            'fund_source' => $this->faker->randomElement(['nationally funded', 'pta']),
            'salary_grade_id' => $this->faker->numberBetween(1, 32),
            'step_increment' => $this->faker->randomElement(range(1, 8)),
            'leave_of_absence_without_pay_count' => $this->faker->numberBetween(0, 30),
            'salary_changed_at' => $this->faker->date,
            'category' => $this->faker->randomElement(['SDO Personnel', 'School Head', 'Elementary School Teacher', 'Junior High School Teacher', 'Senior High School Teacher', 'School Non-teaching Personnel']),
            'job_status' => $this->faker->randomElement($jobStatusOptions),
            'employment_start' => $this->faker->date,
            'employment_end' => $this->faker->date,
            'tin' => $this->faker->numerify('###########'),
            'sss_num' => $this->faker->numerify('##########'),
            'gsis_num' => $this->faker->numerify('###########'),
            'philhealth_num' => $this->faker->numerify('###########'),
            'pagibig_num' => $this->faker->numerify('###########'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
