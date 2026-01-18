<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidateRole = \App\Models\Role::where('name', 'candidate')->first();
        $candidates = User::where('role_id', $candidateRole->id)->get();
        $jobPostings = JobPosting::where('status', 'active')->get();

        if ($candidates->isEmpty()) {
            $this->command->warn('No candidates found. Please run UserSeeder first.');
            return;
        }

        if ($jobPostings->isEmpty()) {
            $this->command->warn('No job postings found. Please run JobPostingSeeder first.');
            return;
        }

        $hrRole = \App\Models\Role::where('name', 'hr')->first();
        $hr = User::where('role_id', $hrRole->id)->first();

        $applications = [
            // Aplikasi dari kandidat pertama - Software Engineer Position
            [
                'code' => 'AP-2025-001',
                'job_posting_id' => $jobPostings->first()->id,
                'candidate_id' => $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->first()->name,
                    'email' => $candidates->first()->email,
                    'phone' => $candidates->first()->phone ?? '081234567893',
                    'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                    'birth_date' => '1995-05-15',
                    'gender' => 'male',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Universitas Indonesia',
                            'major' => 'Teknik Informatika',
                            'year' => '2017'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'Software Engineer',
                            'company' => 'PT. Tech Solutions',
                            'duration' => '2020-2023',
                            'description' => 'Develop web applications using Laravel and Vue.js'
                        ]
                    ],
                ]),
                'cover_letter' => "Kepada Yth. Tim Rekrutmen,\n\nSaya sangat tertarik dengan posisi Software Engineer. Dengan pengalaman 3 tahun di Laravel dan Vue.js.\n\nHormat saya,\n" . $candidates->first()->name,
                'cv_file' => 'cvs/candidate1_software_engineer.pdf',
                'status' => 'interview_scheduled',
                'reviewed_by' => $hr->id,
                'reviewed_at' => Carbon::now()->subDays(23),
            ],
            
            // Aplikasi dari kandidat kedua - UI/UX Designer
            [
                'code' => 'AP-2025-002',
                'job_posting_id' => $jobPostings->skip(1)->first()->id ?? $jobPostings->first()->id,
                'candidate_id' => $candidates->skip(1)->first()->id ?? $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->skip(1)->first()->name ?? $candidates->first()->name,
                    'email' => $candidates->skip(1)->first()->email ?? $candidates->first()->email,
                    'phone' => '081234567894',
                    'address' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
                    'birth_date' => '1997-08-22',
                    'gender' => 'female',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Institut Teknologi Bandung',
                            'major' => 'Desain Komunikasi Visual',
                            'year' => '2019'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'UI/UX Designer',
                            'company' => 'Digital Creative Agency',
                            'duration' => '2019-2021',
                            'description' => 'Design UI and create wireframes using Figma'
                        ]
                    ],
                ]),
                'cover_letter' => "Dear Hiring Team,\n\nI am excited to apply for the UI/UX Designer position.\n\nBest regards,\n" . ($candidates->skip(1)->first()->name ?? $candidates->first()->name),
                'cv_file' => 'cvs/candidate2_uiux_designer.pdf',
                'portfolio_file' => 'portfolios/candidate2_portfolio.pdf',
                'status' => 'offered',
                'reviewed_by' => $hr->id,
                'reviewed_at' => Carbon::now()->subDays(16),
            ],

            // Aplikasi dari kandidat ketiga - Marketing Manager
            [
                'code' => 'AP-2025-003',
                'job_posting_id' => $jobPostings->skip(2)->first()->id ?? $jobPostings->first()->id,
                'candidate_id' => $candidates->skip(2)->first()->id ?? $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->skip(2)->first()->name ?? $candidates->first()->name,
                    'email' => $candidates->skip(2)->first()->email ?? $candidates->first()->email,
                    'phone' => '081234567895',
                    'address' => 'Jl. HR Rasuna Said No. 789, Jakarta Selatan',
                    'birth_date' => '1988-03-10',
                    'gender' => 'male',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Universitas Gajah Mada',
                            'major' => 'Marketing Management',
                            'year' => '2010'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'Marketing Manager',
                            'company' => 'PT. Global Brands',
                            'duration' => '2015-2023',
                            'description' => 'Lead marketing team and develop strategies'
                        ]
                    ],
                ]),
                'cover_letter' => "Kepada Yth. HRD Manager,\n\nSaya tertarik bergabung sebagai Marketing Manager.\n\nSalam hormat",
                'cv_file' => 'cvs/candidate3_marketing_manager.pdf',
                'status' => 'screening_passed',
                'reviewed_by' => $hr->id,
                'reviewed_at' => Carbon::now()->subDays(10),
            ],

            // Aplikasi dari kandidat keempat - Data Analyst
            [
                'code' => 'AP-2025-004',
                'job_posting_id' => $jobPostings->skip(3)->first()->id ?? $jobPostings->first()->id,
                'candidate_id' => $candidates->skip(3)->first()->id ?? $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->skip(3)->first()->name ?? $candidates->first()->name,
                    'email' => $candidates->skip(3)->first()->email ?? $candidates->first()->email,
                    'phone' => '081234567896',
                    'address' => 'Jl. Thamrin No. 234, Jakarta Pusat',
                    'birth_date' => '1994-11-05',
                    'gender' => 'female',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Universitas Brawijaya',
                            'major' => 'Statistika',
                            'year' => '2016'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'Data Analyst',
                            'company' => 'E-commerce Startup',
                            'duration' => '2018-2023',
                            'description' => 'Analyze data and create dashboards'
                        ]
                    ],
                ]),
                'cover_letter' => "Dear Recruitment Team,\n\nI am interested in the Data Analyst position.\n\nBest regards",
                'cv_file' => 'cvs/candidate4_data_analyst.pdf',
                'status' => 'submitted',
            ],

            // Aplikasi dari kandidat kelima - HR Specialist  
            [
                'code' => 'AP-2025-005',
                'job_posting_id' => $jobPostings->skip(4)->first()->id ?? $jobPostings->first()->id,
                'candidate_id' => $candidates->skip(4)->first()->id ?? $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->skip(4)->first()->name ?? $candidates->first()->name,
                    'email' => $candidates->skip(4)->first()->email ?? $candidates->first()->email,
                    'phone' => '081234567897',
                    'address' => 'Jl. Kuningan No. 567, Jakarta Selatan',
                    'birth_date' => '1992-07-18',
                    'gender' => 'male',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Universitas Airlangga',
                            'major' => 'Psikologi',
                            'year' => '2014'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'HR Generalist',
                            'company' => 'PT. Manufacturing Indonesia',
                            'duration' => '2016-2023',
                            'description' => 'Recruitment and employee relations'
                        ]
                    ],
                ]),
                'cover_letter' => "Kepada Yth. Tim HRD,\n\nSaya tertarik dengan posisi HR Specialist.\n\nTerima kasih",
                'cv_file' => 'cvs/candidate5_hr_specialist.pdf',
                'status' => 'rejected_admin',
                'rejection_reason' => 'Pengalaman kurang sesuai dengan kebutuhan saat ini.',
                'reviewed_by' => $hr->id,
                'reviewed_at' => Carbon::now()->subDays(3),
            ],

            // Aplikasi tambahan
            [
                'code' => 'AP-2025-006',
                'job_posting_id' => $jobPostings->skip(3)->first()->id ?? $jobPostings->first()->id,
                'candidate_id' => $candidates->first()->id,
                'candidate_snapshot' => json_encode([
                    'full_name' => $candidates->first()->name,
                    'email' => $candidates->first()->email,
                    'phone' => '081234567893',
                    'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                    'birth_date' => '1995-05-15',
                    'gender' => 'male',
                    'education' => [
                        [
                            'degree' => 'S1',
                            'institution' => 'Universitas Indonesia',
                            'major' => 'Teknik Informatika',
                            'year' => '2017'
                        ]
                    ],
                    'experience' => [
                        [
                            'position' => 'Software Engineer',
                            'company' => 'PT. Tech Solutions',
                            'duration' => '2020-2023',
                            'description' => 'Web development and data analysis'
                        ]
                    ],
                ]),
                'cover_letter' => "Dear Hiring Manager,\n\nI also have analytical skills.\n\nRegards",
                'cv_file' => 'cvs/candidate1_data_analyst.pdf',
                'status' => 'submitted',
            ],
        ];

        foreach ($applications as $application) {
            Application::create($application);
        }

        $this->command->info('Applications seeded successfully!');
    }
}
