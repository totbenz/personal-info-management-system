<?php

namespace Database\Factories;

use App\Models\ContactPerson;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactPersonFactory extends Factory
{
    protected $model = ContactPerson::class;

    public function definition(): array
    {
        return [
            'personnel_id' => Personnel::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->optional()->safeEmail(),
            'mobile_no' => $this->faker->numerify('09#########'),
        ];
    }
}
