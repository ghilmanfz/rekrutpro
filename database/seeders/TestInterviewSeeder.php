<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interview;
use Carbon\Carbon;

class TestInterviewSeeder extends Seeder
{
    public function run()
    {
        Interview::create([
            'application_id' => 1,
            'interviewer_id' => 3,
            'scheduled_at' => Carbon::now()->addDays(2)->setTime(14, 0),
            'duration' => 60,
            'location' => 'Meeting Room A - Head Office',
            'interview_type' => 'onsite',
            'status' => 'scheduled',
            'scheduled_by' => 2,
            'notes' => 'Technical interview untuk posisi Full Stack Developer'
        ]);

        echo "Test interview created successfully!\n";
    }
}
