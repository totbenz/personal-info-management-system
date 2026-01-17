<?php

namespace Database\Factories;

use App\Models\Reference;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferenceFactory extends Factory
{
    protected $model = Reference::class;

    public function definition(): array
    {
        return [
            'personnel_id' => Personnel::factory(),
            'full_name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'tel_no' => $this->faker->numerify('09#########'),
        ];
    }
}
