<?php

namespace Database\Factories;

use App\Models\VoluntaryWork;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoluntaryWorkFactory extends Factory
{
    protected $model = VoluntaryWork::class;

    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-10 years', '-1 year');
        $to = $this->faker->dateTimeBetween($from, 'now');

        return [
            'personnel_id' => Personnel::factory(),
            'organization' => $this->faker->randomElement([
                'Philippine Red Cross',
                'Gawad Kalinga',
                'Habitat for Humanity Philippines',
                'World Vision Philippines',
                'UNICEF Philippines',
                'Save the Children Philippines',
                'Bantay Bata 163',
                'Community Pantry Movement',
                'Local Barangay Council',
                'Parish Youth Ministry',
            ]),
            'position' => $this->faker->randomElement([
                'Volunteer Teacher',
                'Community Organizer',
                'Relief Operations Coordinator',
                'Youth Leader',
                'Program Facilitator',
                'Event Coordinator',
                'Outreach Volunteer',
                'Disaster Response Volunteer',
            ]),
            'inclusive_from' => $from->format('Y-m-d'),
            'inclusive_to' => $to->format('Y-m-d'),
            'hours' => $this->faker->numberBetween(20, 500),
        ];
    }
}
