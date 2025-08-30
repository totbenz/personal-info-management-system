<?php

namespace Database\Factories;

use App\Models\ServiceCreditRequest;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCreditRequestFactory extends Factory
{
    protected $model = ServiceCreditRequest::class;

    public function definition()
    {
        $personnel = Personnel::inRandomOrder()->first();
        return [
            'teacher_id' => $personnel ? $personnel->id : null,
            'requested_days' => $this->faker->randomElement([0.5, 1.0]),
            'work_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'morning_in' => $this->faker->randomElement(['07:30', '08:00', '08:30']),
            'morning_out' => $this->faker->randomElement(['11:30', '12:00']),
            'afternoon_in' => $this->faker->randomElement(['12:30', '13:00', null]),
            'afternoon_out' => $this->faker->randomElement(['16:30', '17:00', null]),
            'total_hours' => $this->faker->randomElement([4.0, 8.0]),
            'reason' => $this->faker->sentence(6),
            'description' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'approved_at' => null,
            'approved_by' => null,
            'admin_notes' => null,
        ];
    }
}
