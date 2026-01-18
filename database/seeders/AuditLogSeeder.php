<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Offer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrRole = \App\Models\Role::where('name', 'hr')->first();
        $hr = User::where('role_id', $hrRole->id)->first();
        
        $interviewerRole = \App\Models\Role::where('name', 'interviewer')->first();
        $interviewer = User::where('role_id', $interviewerRole->id)->first();
        
        $candidateRole = \App\Models\Role::where('name', 'candidate')->first();
        $candidates = User::where('role_id', $candidateRole->id)->get();

        $logs = [];

        // Application audit logs
        $application = Application::first();
        if ($application && $candidates->isNotEmpty()) {
            $logs[] = [
                'user_id' => $candidates->first()->id,
                'action' => 'create',
                'model_type' => 'App\Models\Application',
                'model_id' => $application->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'job_posting_id' => $application->job_posting_id,
                    'status' => 'submitted',
                ]),
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(25),
            ];

            if ($hr) {
                $logs[] = [
                    'user_id' => $hr->id,
                    'action' => 'update',
                    'model_type' => 'App\Models\Application',
                    'model_id' => $application->id,
                    'old_values' => json_encode(['status' => 'submitted']),
                    'new_values' => json_encode(['status' => 'screening_passed']),
                    'ip_address' => '192.168.1.50',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => Carbon::now()->subDays(23),
                ];

                $logs[] = [
                    'user_id' => $hr->id,
                    'action' => 'update',
                    'model_type' => 'App\Models\Application',
                    'model_id' => $application->id,
                    'old_values' => json_encode(['status' => 'screening_passed']),
                    'new_values' => json_encode(['status' => 'interview_scheduled']),
                    'ip_address' => '192.168.1.50',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => Carbon::now()->subDays(20),
                ];
            }
        }

        // Interview audit logs
        $interview = Interview::first();
        if ($interview && $hr) {
            $logs[] = [
                'user_id' => $hr->id,
                'action' => 'create',
                'model_type' => 'App\Models\Interview',
                'model_id' => $interview->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'application_id' => $interview->application_id,
                    'interviewer_id' => $interview->interviewer_id,
                    'status' => 'scheduled',
                ]),
                'ip_address' => '192.168.1.50',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(21),
            ];

            if ($interviewer) {
                $logs[] = [
                    'user_id' => $interviewer->id,
                    'action' => 'update',
                    'model_type' => 'App\Models\Interview',
                    'model_id' => $interview->id,
                    'old_values' => json_encode(['status' => 'scheduled']),
                    'new_values' => json_encode(['status' => 'completed']),
                    'ip_address' => '192.168.1.75',
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'created_at' => Carbon::now()->subDays(20),
                ];
            }
        }

        // Offer audit logs
        $offer = Offer::first();
        if ($offer && $hr) {
            $logs[] = [
                'user_id' => $hr->id,
                'action' => 'create',
                'model_type' => 'App\Models\Offer',
                'model_id' => $offer->id,
                'old_values' => null,
                'new_values' => json_encode([
                    'application_id' => $offer->application_id,
                    'salary' => $offer->salary,
                    'status' => 'pending',
                ]),
                'ip_address' => '192.168.1.50',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(2),
            ];
        }

        // User login logs
        if ($hr) {
            $logs[] = [
                'user_id' => $hr->id,
                'action' => 'login',
                'model_type' => 'App\Models\User',
                'model_id' => $hr->id,
                'old_values' => null,
                'new_values' => json_encode(['login_at' => Carbon::now()->subHours(2)->toDateTimeString()]),
                'ip_address' => '192.168.1.50',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(2),
            ];
        }

        if ($candidates->isNotEmpty()) {
            $logs[] = [
                'user_id' => $candidates->first()->id,
                'action' => 'login',
                'model_type' => 'App\Models\User',
                'model_id' => $candidates->first()->id,
                'old_values' => null,
                'new_values' => json_encode(['login_at' => Carbon::now()->subHours(1)->toDateTimeString()]),
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(1),
            ];
        }

        foreach ($logs as $log) {
            AuditLog::create($log);
        }

        $this->command->info('Audit Logs seeded successfully!');
    }
}
