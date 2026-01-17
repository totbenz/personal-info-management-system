<?php

namespace Database\Factories;

use App\Models\WorkExperience;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    protected $model = WorkExperience::class;

    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-15 years', '-1 year');
        $to = $this->faker->dateTimeBetween($from, 'now');
        $isGovService = $this->faker->boolean(70); // 70% chance of government service

        return [
            'personnel_id' => Personnel::factory(),
            'title' => $this->faker->randomElement([
                'Teacher I',
                'Teacher II',
                'Teacher III',
                'Master Teacher I',
                'Master Teacher II',
                'School Principal',
                'Assistant Principal',
                'Department Head',
                'Guidance Counselor',
                'Administrative Officer',
            ]),
            'company' => $isGovService
                ? $this->faker->city() . ' ' . $this->faker->randomElement(['National High School', 'Elementary School', 'Integrated School', 'Science High School'])
                : $this->faker->company(),
            'inclusive_from' => $from->format('Y-m-d'),
            'inclusive_to' => $to->format('Y-m-d'),
            'monthly_salary' => $this->faker->numberBetween(15000, 50000),
            'paygrade_step_increment' => $isGovService
                ? $this->faker->randomElement(['Grade 11 Step 1', 'Grade 12 Step 2', 'Grade 13 Step 3', 'Grade 14 Step 4'])
                : null,
            'appointment' => $this->faker->randomElement(['Permanent', 'Temporary', 'Contractual', 'Casual']),
            'is_gov_service' => $isGovService,
        ];
    }

    public function government(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_gov_service' => true,
            'company' => $this->faker->city() . ' National High School',
            'paygrade_step_increment' => $this->faker->randomElement(['Grade 11 Step 1', 'Grade 12 Step 2', 'Grade 13 Step 3']),
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_gov_service' => false,
            'paygrade_step_increment' => null,
        ]);
    }
}
