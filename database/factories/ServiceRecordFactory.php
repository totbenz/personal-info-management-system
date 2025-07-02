<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\ServiceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRecord>
 */
class ServiceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromDate = $this->faker->dateTimeBetween('-5 years', 'now');
        $toDate = $this->faker->optional()->dateTimeBetween($fromDate, 'now');

        return [
            'personnel_id' => 3,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'position_id' => 1,
            'appointment_status' => $this->faker->randomElement(['Permanent', 'Temporary', 'Contractual']),
            'salary' => $this->faker->randomFloat(2, 20000, 100000),
            'station' => $this->faker->numberBetween(1,10),
            'branch' => $this->faker->numberBetween(1,10),
            'lv_wo_pay' => $this->faker->optional()->numberBetween(1, 30) . ' days',
            'separation_date_cause' => $this->faker->optional()->sentence(),
        ];
    }
}
