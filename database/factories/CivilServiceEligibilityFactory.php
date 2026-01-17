<?php

namespace Database\Factories;

use App\Models\CivilServiceEligibility;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CivilServiceEligibilityFactory extends Factory
{
    protected $model = CivilServiceEligibility::class;

    public function definition(): array
    {
        $examDate = $this->faker->dateTimeBetween('-10 years', '-1 year');

        return [
            'personnel_id' => Personnel::factory(),
            'title' => $this->faker->randomElement([
                'Career Service Professional',
                'Career Service Sub-Professional',
                'RA 1080 (Teacher)',
                'PBET (Professional Board Examination for Teachers)',
                'Licensure Examination for Teachers (LET)',
                'Civil Service Eligibility',
            ]),
            'rating' => $this->faker->randomFloat(2, 75, 99),
            'date_of_exam' => $examDate->format('Y-m-d'),
            'place_of_exam' => $this->faker->city() . ', Philippines',
            'license_num' => $this->faker->numerify('LIC-####-####'),
            'license_date_of_validity' => $this->faker->dateTimeBetween($examDate, '+10 years')->format('Y-m-d'),
        ];
    }
}
