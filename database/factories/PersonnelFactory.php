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
            'school_id' => School::inRandomOrder()->first()->id, // Fetch a random existing school_id
            'position_id' => Position::inRandomOrder()->first()->id, // Fetch a random existing position_id
            'appointment' => $this->faker->randomElement(['regular', 'part-time', 'temporary', 'contract']),
            'fund_source' => $this->faker->randomElement(['nationally funded', 'pta']),
            'salary_grade' => $this->faker->randomElement(range(1, 20)),
            'step' => $this->faker->optional()->randomElement(range(1, 8)),
            'category' => $this->faker->randomElement(['SDO Personnel', 'School Head', 'Elementary School Teacher', 'Junior High School Teacher', 'Senior High School Teacher', 'School Non-teaching Personnel']),
            'job_status' => $this->faker->randomElement(['active', 'vacation', 'terminated', 'on leave', 'suspended', 'resigned', 'probation']),
            'employment_start' => $this->faker->date,
            'employment_end' => $this->faker->optional()->date,
            'tin' => $this->faker->optional()->numerify('###########'),
            'sss_num' => $this->faker->optional()->numerify('##########'),
            'gsis_num' => $this->faker->optional()->numerify('###########'),
            'philhealth_num' => $this->faker->optional()->numerify('###########'),
            'pagibig_num' => $this->faker->optional()->numerify('###########'),
            'salary' => $this->faker->numberBetween(20000, 100000), // Add salary attribute
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
