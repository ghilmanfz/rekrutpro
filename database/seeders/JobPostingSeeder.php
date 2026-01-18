<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Division;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrRole = \App\Models\Role::where('name', 'hr')->first();
        $hr = User::where('role_id', $hrRole->id)->first();
        
        if (!$hr) {
            $this->command->warn('No HR user found. Please run UserSeeder first.');
            return;
        }

        $positions = Position::all();
        $divisions = Division::all();
        $locations = Location::all();

        if ($positions->isEmpty() || $divisions->isEmpty() || $locations->isEmpty()) {
            $this->command->warn('Please run PositionSeeder, DivisionSeeder, and LocationSeeder first.');
            return;
        }

        $jobPostings = [
            [
                'code' => 'SE001',
                'position_id' => $positions->where('title', 'Software Engineer')->first()->id ?? $positions->first()->id,
                'division_id' => $divisions->where('name', 'IT')->first()->id ?? $divisions->first()->id,
                'location_id' => $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'Software Engineer - Laravel Specialist',
                'description' => "Kami mencari Software Engineer berpengalaman untuk bergabung dengan tim pengembangan kami.\n\nTanggung jawab:\n- Mengembangkan dan memelihara aplikasi web\n- Berkolaborasi dengan tim untuk merancang solusi teknis\n- Menulis kode yang bersih dan terdokumentasi\n- Melakukan code review\n\nKualifikasi:\n- Minimal 2 tahun pengalaman dalam pengembangan web\n- Menguasai Laravel, PHP, MySQL\n- Familiar dengan Git dan agile methodology\n- Kemampuan komunikasi yang baik",
                'requirements' => "- S1 Teknik Informatika/Sistem Informasi\n- Pengalaman minimal 2 tahun\n- Menguasai Laravel, Vue.js/React\n- Memahami RESTful API\n- Pengalaman dengan testing (PHPUnit, Jest)",
                'responsibilities' => "- Develop dan maintain web applications\n- Write clean, maintainable code\n- Collaborate dengan tim\n- Code review\n- Dokumentasi teknis",
                'benefits' => "- Gaji kompetitif\n- BPJS Kesehatan & Ketenagakerjaan\n- Flexible working hours\n- Learning budget\n- Team building activities",
                'quota' => 2,
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'salary_min' => 8000000,
                'salary_max' => 15000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(30),
                'status' => 'active',
            ],
            [
                'code' => 'UIUX001',
                'position_id' => $positions->where('title', 'UI/UX Designer')->first()->id ?? $positions->skip(1)->first()->id,
                'division_id' => $divisions->where('name', 'IT')->first()->id ?? $divisions->first()->id,
                'location_id' => $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'UI/UX Designer - Product Design',
                'description' => "Bergabunglah dengan tim kreatif kami sebagai UI/UX Designer!\n\nTanggung jawab:\n- Merancang antarmuka pengguna yang intuitif dan menarik\n- Melakukan riset pengguna dan analisis kompetitor\n- Membuat wireframe, mockup, dan prototype\n- Berkolaborasi dengan developer untuk implementasi\n\nKualifikasi:\n- Minimal 1 tahun pengalaman sebagai UI/UX Designer\n- Menguasai Figma/Adobe XD\n- Memahami prinsip design thinking\n- Portfolio yang kuat",
                'requirements' => "- S1 Desain Grafis/Multimedia atau setara\n- Pengalaman minimal 1 tahun\n- Menguasai Figma, Adobe XD, atau Sketch\n- Memahami design system\n- Portfolio wajib dilampirkan",
                'responsibilities' => "- Design user interfaces\n- Conduct user research\n- Create wireframes and prototypes\n- Collaborate dengan developer\n- Maintain design system",
                'benefits' => "- Gaji kompetitif\n- BPJS\n- Peralatan kerja (Laptop, Monitor)\n- Flexible hours\n- Creative freedom",
                'quota' => 1,
                'employment_type' => 'full_time',
                'experience_level' => 'junior',
                'salary_min' => 6000000,
                'salary_max' => 12000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(20),
                'status' => 'active',
            ],
            [
                'code' => 'MM001',
                'position_id' => $positions->where('title', 'Marketing Manager')->first()->id ?? $positions->skip(2)->first()->id,
                'division_id' => $divisions->where('name', 'Marketing')->first()->id ?? $divisions->skip(1)->first()->id,
                'location_id' => $locations->skip(1)->first()->id ?? $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'Marketing Manager - Digital Marketing Lead',
                'description' => "Kami mencari Marketing Manager yang berpengalaman untuk memimpin tim marketing kami.\n\nTanggung jawab:\n- Mengembangkan strategi marketing yang efektif\n- Mengelola campaign marketing digital dan konvensional\n- Menganalisis performa marketing dan ROI\n- Memimpin dan mengembangkan tim marketing\n- Mengelola budget marketing\n\nKualifikasi:\n- Minimal 5 tahun pengalaman di bidang marketing\n- Pengalaman memimpin tim\n- Menguasai digital marketing tools\n- Analytical thinking yang kuat",
                'requirements' => "- S1 Marketing/Komunikasi/Management\n- Pengalaman minimal 5 tahun di marketing\n- Pengalaman leadership minimal 2 tahun\n- Menguasai Google Analytics, Facebook Ads, Google Ads\n- Kemampuan presentasi dan negosiasi yang baik",
                'responsibilities' => "- Develop marketing strategy\n- Lead marketing team\n- Manage campaigns\n- Analyze marketing performance\n- Budget management",
                'benefits' => "- Gaji menarik + bonus performa\n- BPJS + Asuransi swasta\n- Flexible hours\n- Annual leave 15 hari\n- Career development",
                'quota' => 1,
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'salary_min' => 15000000,
                'salary_max' => 25000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(15),
                'status' => 'active',
            ],
            [
                'code' => 'DA001',
                'position_id' => $positions->where('title', 'Data Analyst')->first()->id ?? $positions->skip(3)->first()->id,
                'division_id' => $divisions->where('name', 'IT')->first()->id ?? $divisions->first()->id,
                'location_id' => $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'Data Analyst - Business Intelligence',
                'description' => "Posisi contract 12 bulan sebagai Data Analyst.\n\nTanggung jawab:\n- Menganalisis data bisnis dan memberikan insights\n- Membuat dashboard dan laporan visualisasi data\n- Berkolaborasi dengan tim untuk kebutuhan data\n- Melakukan data cleaning dan preprocessing\n\nKualifikasi:\n- Minimal 2 tahun pengalaman sebagai Data Analyst\n- Menguasai SQL, Python/R\n- Familiar dengan tools visualisasi data (Tableau, Power BI)\n- Analytical dan detail-oriented",
                'requirements' => "- S1 Statistika/Matematika/Teknik Informatika\n- Pengalaman minimal 2 tahun\n- Menguasai SQL (MySQL/PostgreSQL)\n- Menguasai Python (pandas, numpy) atau R\n- Pengalaman dengan Tableau/Power BI",
                'responsibilities' => "- Analyze business data\n- Create dashboards and reports\n- Data cleaning and preprocessing\n- Provide actionable insights\n- Collaborate with stakeholders",
                'benefits' => "- Gaji kompetitif\n- BPJS\n- Laptop provided\n- Learning resources\n- Extension opportunity",
                'quota' => 1,
                'employment_type' => 'contract',
                'experience_level' => 'mid',
                'salary_min' => 7000000,
                'salary_max' => 13000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(10),
                'status' => 'active',
            ],
            [
                'code' => 'HR001',
                'position_id' => $positions->where('title', 'HR Specialist')->first()->id ?? $positions->skip(4)->first()->id,
                'division_id' => $divisions->where('name', 'Human Resources')->first()->id ?? $divisions->skip(2)->first()->id,
                'location_id' => $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'HR Specialist - Recruitment & Employee Relations',
                'description' => "Bergabunglah dengan tim HR kami!\n\nTanggung jawab:\n- Mengelola proses rekrutmen end-to-end\n- Mengembangkan program employee engagement\n- Mengelola administrasi kepegawaian\n- Menangani employee relations\n\nKualifikasi:\n- Minimal 2 tahun pengalaman di HR\n- Memahami employment law\n- Kemampuan komunikasi dan interpersonal yang baik\n- Detail-oriented dan organized",
                'requirements' => "- S1 Psikologi/Management/Hukum\n- Pengalaman minimal 2 tahun di HR\n- Memahami UU Ketenagakerjaan\n- Menguasai MS Office\n- Bersertifikat HR (diutamakan)",
                'responsibilities' => "- Manage recruitment process\n- Employee engagement programs\n- HR administration\n- Employee relations\n- Performance management support",
                'benefits' => "- Gaji kompetitif\n- BPJS\n- Training & development\n- Work-life balance\n- Career progression",
                'quota' => 1,
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'salary_min' => 6000000,
                'salary_max' => 10000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(7),
                'status' => 'active',
            ],
            [
                'code' => 'INT001',
                'position_id' => $positions->first()->id,
                'division_id' => $divisions->first()->id,
                'location_id' => $locations->first()->id,
                'created_by' => $hr->id,
                'title' => 'IT Internship Program - Web Development',
                'description' => "Program internship 6 bulan untuk fresh graduate.\n\nTanggung jawab:\n- Membantu tim dalam project development\n- Belajar best practices software development\n- Dokumentasi dan testing\n\nKualifikasi:\n- Fresh graduate atau mahasiswa semester akhir\n- Antusias belajar teknologi baru\n- Basic programming skills",
                'requirements' => "- Mahasiswa S1 Teknik Informatika semester akhir atau fresh graduate\n- IPK minimal 3.0\n- Memiliki basic programming (PHP/Python/Java)\n- Dapat bekerja full time selama 6 bulan",
                'responsibilities' => "- Assist development team\n- Learn software development best practices\n- Documentation\n- Testing\n- Code review participation",
                'benefits' => "- Uang saku\n- Sertifikat\n- Mentoring\n- Pengalaman kerja\n- Potential full-time offer",
                'quota' => 3,
                'employment_type' => 'internship',
                'experience_level' => 'junior',
                'salary_min' => 3000000,
                'salary_max' => 4000000,
                'salary_currency' => 'IDR',
                'published_at' => Carbon::now()->subDays(60),
                'closed_at' => Carbon::now()->subDays(5),
                'status' => 'closed',
            ],
        ];

        foreach ($jobPostings as $posting) {
            JobPosting::create($posting);
        }

        $this->command->info('Job Postings seeded successfully!');
    }
}
