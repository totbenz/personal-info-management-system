<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }

        $events = [
            [
                'title' => 'Personnel Monthly Meeting',
                'description' => 'Monthly coordination meeting for all personnel administrators',
                'start_date' => Carbon::now(),
                'start_time' => '09:00:00',
                'type' => Event::TYPE_MEETING,
                'location' => 'Conference Room A',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Digital Literacy Training',
                'description' => 'Training session on digital tools and technologies for teachers',
                'start_date' => Carbon::now()->addDay(),
                'start_time' => '14:00:00',
                'type' => Event::TYPE_TRAINING,
                'location' => 'Training Center',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Quarterly School Inspection',
                'description' => 'Regular inspection of school facilities and operations',
                'start_date' => Carbon::now()->addDays(3),
                'start_time' => '10:00:00',
                'type' => Event::TYPE_INSPECTION,
                'location' => 'Various Schools',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Annual Loyalty Awards Ceremony',
                'description' => 'Recognition ceremony for outstanding personnel service',
                'start_date' => Carbon::now()->addDays(7),
                'start_time' => '15:00:00',
                'type' => Event::TYPE_CEREMONY,
                'location' => 'Main Auditorium',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Budget Planning Session',
                'description' => 'Annual budget planning and resource allocation meeting',
                'start_date' => Carbon::now()->addDays(10),
                'start_time' => '11:00:00',
                'type' => Event::TYPE_MEETING,
                'location' => 'Finance Office',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Teacher Development Workshop',
                'description' => 'Professional development workshop for teaching methodologies',
                'start_date' => Carbon::now()->addDays(14),
                'start_time' => '13:00:00',
                'type' => Event::TYPE_TRAINING,
                'location' => 'Professional Development Center',
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Performance Review Deadline',
                'description' => 'Deadline for submitting annual performance reviews',
                'start_date' => Carbon::now()->addDays(21),
                'is_all_day' => true,
                'type' => Event::TYPE_DEADLINE,
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Safety Compliance Inspection',
                'description' => 'Annual safety and compliance inspection of all facilities',
                'start_date' => Carbon::now()->addDays(28),
                'start_time' => '08:00:00',
                'type' => Event::TYPE_INSPECTION,
                'location' => 'All School Facilities',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
