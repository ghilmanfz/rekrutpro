<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interviewerRole = \App\Models\Role::where('name', 'interviewer')->first();
        $interviewers = User::where('role_id', $interviewerRole->id)->get();
        
        $hrRole = \App\Models\Role::where('name', 'hr')->first();
        $hr = User::where('role_id', $hrRole->id)->first();

        if ($interviewers->isEmpty()) {
            $this->command->warn('No interviewers found. Please run UserSeeder first.');
            return;
        }

        $applications = Application::whereIn('status', ['interview_scheduled', 'offered'])->get();

        if ($applications->isEmpty()) {
            $this->command->warn('No applications with interview status found. Please run ApplicationSeeder first.');
            return;
        }

    $interviews = [];

    // make interviewer assignment deterministic
    $interviewer1 = $interviewers->values()->get(0)->id ?? null;
    $interviewer2 = $interviewers->values()->get(1)->id ?? $interviewer1;

        // Interview untuk aplikasi pertama (Software Engineer) - Multiple stages
        if ($applications->count() > 0) {
            $app1 = $applications->first();
            
            // HR Interview - Completed (fixed date)
            $interviews[] = [
                'application_id' => $app1->id,
                'interviewer_id' => $hr->id,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-01 10:00:00'),
                'duration' => 30,
                'interview_type' => 'onsite',
                'location' => 'Meeting Room A - HR Interview untuk Software Engineer position',
                'status' => 'completed',
                'notes' => 'HR Interview: Kandidat sangat komunikatif dan antusias. Rekomedasi lanjut ke technical interview.',
            ];

            // Technical Interview - Completed (fixed date)
            $interviews[] = [
                'application_id' => $app1->id,
                'interviewer_id' => $interviewer1,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-05 14:00:00'),
                'duration' => 60,
                'interview_type' => 'video',
                'location' => 'Google Meet - Technical Interview',
                'status' => 'completed',
                'notes' => 'Technical Interview: Covering backend and system design. Score: 90/100. PASS.',
            ];

            // Final Interview - Scheduled (future fixed date)
            $interviews[] = [
                'application_id' => $app1->id,
                'interviewer_id' => $interviewer2,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-25 15:00:00'),
                'duration' => 45,
                'interview_type' => 'onsite',
                'location' => 'CEO Office',
                'status' => 'scheduled',
                'notes' => 'Final interview with Director of Engineering to discuss team fit and roadmap.',
            ];
        }

        // Interview untuk aplikasi kedua (UI/UX Designer) - Completed all stages
        if ($applications->count() > 1) {
            $app2 = $applications->skip(1)->first();

            // HR Interview - Completed (Design challenge)
            $interviews[] = [
                'application_id' => $app2->id,
                'interviewer_id' => $interviewer1,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-07 13:00:00'),
                'duration' => 90,
                'interview_type' => 'video',
                'location' => 'Google Meet - Design Challenge Session',
                'status' => 'completed',
                'notes' => 'Design Challenge: outstanding portfolio and UX thinking.',
            ];

            // Final Interview - Completed (fixed date)
            $interviews[] = [
                'application_id' => $app2->id,
                'interviewer_id' => $interviewer2,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-10 10:00:00'),
                'duration' => 30,
                'interview_type' => 'onsite',
                'location' => 'Meeting Room A - Final Interview',
                'status' => 'completed',
                'notes' => 'Final Interview: discussion about team collaboration and design system implementation.',
            ];
        }

        // Interview untuk aplikasi ketiga - Completed (NEEDS ASSESSMENT)
        if ($applications->count() > 2) {
            $app3 = $applications->skip(2)->first();
            
            $interviews[] = [
                'application_id' => $app3->id,
                'interviewer_id' => $interviewer1,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-02 14:00:00'),
                'duration' => 60,
                'interview_type' => 'video',
                'location' => 'Google Meet - Technical Interview',
                'status' => 'completed',
                'notes' => 'Technical Interview: solid performance.',
            ];
        }

        // Interview untuk aplikasi keempat - Scheduled untuk minggu ini
        if ($applications->count() > 3) {
            $app4 = $applications->skip(3)->first();
            
            // Upcoming interview this week (fixed)
            $interviews[] = [
                'application_id' => $app4->id,
                'interviewer_id' => $hr->id,
                'scheduled_by' => $hr->id,
                'scheduled_at' => Carbon::parse('2026-01-20 14:30:00'),
                'duration' => 45,
                'interview_type' => 'video',
                'location' => 'Zoom Meeting',
                'status' => 'scheduled',
                'notes' => 'Initial screening untuk posisi Marketing Manager. Focus: leadership experience, marketing strategy.',
            ];
        }

        foreach ($interviews as $interview) {
            Interview::create($interview);
        }

        $this->command->info('Interviews seeded successfully!');
    }
}
