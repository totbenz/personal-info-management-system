<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $addressType = $this->faker->randomElement(['permanent', 'residential']);

        return [
            'personnel_id' => Personnel::factory(),
            'address_type' => $addressType,
            'house_no' => $this->faker->buildingNumber(),
            'street' => $this->faker->streetName(),
            'subdivision' => $this->faker->optional()->citySuffix() . ' Subdivision',
            'barangay' => 'Barangay ' . $this->faker->lastName(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'region' => $this->faker->randomElement(['Region I', 'Region II', 'Region III', 'Region IV-A', 'Region IV-B', 'Region V', 'NCR']),
            'zip_code' => $this->faker->postcode(),
        ];
    }

    public function permanent(): static
    {
        return $this->state(fn (array $attributes) => [
            'address_type' => 'permanent',
        ]);
    }

    public function residential(): static
    {
        return $this->state(fn (array $attributes) => [
            'address_type' => 'residential',
        ]);
    }
}
