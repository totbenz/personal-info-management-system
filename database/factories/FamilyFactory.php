<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    protected $model = Family::class;

    public function definition(): array
    {
        $relationship = $this->faker->randomElement(['father', 'mother', 'spouse', 'children']);

        return [
            'personnel_id' => Personnel::factory(),
            'relationship' => $relationship,
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->lastName(),
            'last_name' => $this->faker->lastName(),
            'name_extension' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'II', 'III']),
            'occupation' => $this->faker->optional()->jobTitle(),
            'employer_business_name' => $this->faker->optional()->company(),
            'business_address' => $this->faker->optional()->address(),
            'telephone_number' => $this->faker->optional()->numerify('(02) ####-####'),
            'date_of_birth' => $this->faker->optional()->date('Y-m-d', '-20 years'),
        ];
    }

    public function father(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'father',
            'first_name' => $this->faker->firstNameMale(),
        ]);
    }

    public function mother(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'mother',
            'first_name' => $this->faker->firstNameFemale(),
        ]);
    }

    public function spouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'spouse',
        ]);
    }

    public function children(): static
    {
        return $this->state(fn (array $attributes) => [
            'relationship' => 'children',
            'date_of_birth' => $this->faker->date('Y-m-d', '-5 years'),
        ]);
    }
}
