<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Application;
use Carbon\Carbon;

class TestOfferSeeder extends Seeder
{
    public function run()
    {
        // Get first application that has status interview_passed
        $application = Application::where('status', 'interview_passed')->first();
        
        if (!$application) {
            // If no interview_passed, get any application and update its status
            $application = Application::first();
            if ($application) {
                $application->update([
                    'status' => 'interview_passed',
                    'interview_passed_at' => now()
                ]);
            }
        }

        if ($application) {
            Offer::create([
                'application_id' => $application->id,
                'offered_by' => 2, // HR user
                'position_title' => $application->jobPosting->title,
                'salary' => 12000000,
                'salary_currency' => 'IDR',
                'salary_period' => 'monthly',
                'benefits' => 'BPJS Kesehatan, BPJS Ketenagakerjaan, Tunjangan Transportasi, Tunjangan Makan',
                'contract_type' => 'permanent',
                'start_date' => Carbon::now()->addDays(30),
                'valid_until' => Carbon::now()->addDays(14),
                'status' => 'pending',
                'terms_and_conditions' => 'Kontrak kerja permanen dengan masa percobaan 3 bulan',
                'internal_notes' => 'Kandidat sangat potensial, hasil interview excellent'
            ]);

            // Update application status
            $application->update([
                'status' => 'offered',
                'offered_at' => now()
            ]);

            echo "Test offer created successfully!\n";
            echo "Application ID: {$application->id}\n";
            echo "Candidate: {$application->candidate->name}\n";
        } else {
            echo "No application found. Please create an application first.\n";
        }
    }
}
