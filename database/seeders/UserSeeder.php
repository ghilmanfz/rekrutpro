<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const LEGACY_CANDIDATE_PROFILE_VALUES = [
        'candidate1@example.com' => [
            'date_of_birth' => '1995-05-15',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'education' => 'S1',
            'experience' => 'Software Engineer di PT. Tech Solutions (2020-2023). Mengembangkan aplikasi web menggunakan Laravel dan Vue.js.',
            'skills' => 'PHP, Laravel, Vue.js, MySQL, REST API',
        ],
        'candidate2@example.com' => [
            'date_of_birth' => '1997-08-22',
            'address' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
            'education' => 'S1',
            'experience' => 'UI/UX Designer di Digital Creative Agency (2019-2021). Mendesain UI dan membuat wireframe menggunakan Figma.',
            'skills' => 'Figma, UI Design, UX Research, Wireframing, Prototyping',
        ],
        'candidate3@example.com' => [
            'date_of_birth' => '1988-03-10',
            'address' => 'Jl. HR Rasuna Said No. 789, Jakarta Selatan',
            'education' => 'S1',
            'experience' => 'Marketing Manager di PT. Global Brands (2015-2023). Memimpin tim marketing dan menyusun strategi kampanye.',
            'skills' => 'Marketing Strategy, Leadership, Campaign Management, Brand Management, Communication',
        ],
        'candidate4@example.com' => [
            'date_of_birth' => '1994-11-05',
            'address' => 'Jl. Thamrin No. 234, Jakarta Pusat',
            'education' => 'S1',
            'experience' => 'Data Analyst di startup e-commerce (2018-2023). Menganalisis data dan membuat dashboard performa bisnis.',
            'skills' => 'SQL, Excel, Data Analysis, Dashboard, Python',
        ],
        'candidate5@example.com' => [
            'date_of_birth' => '1992-07-18',
            'address' => 'Jl. Kuningan No. 567, Jakarta Selatan',
            'education' => 'S1',
            'experience' => 'HR Generalist di PT. Manufacturing Indonesia (2016-2023). Menangani rekrutmen dan employee relations.',
            'skills' => 'Recruitment, Interviewing, Employee Relations, HR Administration, Communication',
        ],
    ];

    


    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $hrRole = Role::where('name', 'hr')->first();
        $interviewerRole = Role::where('name', 'interviewer')->first();
        $candidateRole = Role::where('name', 'candidate')->first();
        
        $hrDivision = Division::where('code', 'HR')->first();
        $itDivision = Division::where('code', 'IT')->first();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@rekrutpro.com',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'division_id' => null,
                'phone' => '081234567890',
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'name' => 'Alice Smith',
                'email' => 'hr@rekrutpro.com',
                'password' => Hash::make('password'),
                'role_id' => $hrRole->id,
                'division_id' => $hrDivision->id,
                'phone' => '081234567891',
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'interviewer@rekrutpro.com',
                'password' => Hash::make('password'),
                'role_id' => $interviewerRole->id,
                'division_id' => $itDivision->id,
                'phone' => '081234567892',
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'name' => 'John Developer',
                'email' => 'candidate1@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567893',
                'is_active' => true,
                'is_verified' => true,
                'registration_step' => 5,
                'registration_completed' => true,
            ],
            [
                'name' => 'Sarah Designer',
                'email' => 'candidate2@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567894',
                'is_active' => true,
                'is_verified' => true,
                'registration_step' => 5,
                'registration_completed' => true,
            ],
            [
                'name' => 'Michael Marketing',
                'email' => 'candidate3@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567895',
                'is_active' => true,
                'is_verified' => true,
                'registration_step' => 5,
                'registration_completed' => true,
            ],
            [
                'name' => 'Emma Analyst',
                'email' => 'candidate4@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567896',
                'is_active' => true,
                'is_verified' => true,
                'registration_step' => 5,
                'registration_completed' => true,
            ],
            [
                'name' => 'David HR',
                'email' => 'candidate5@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567897',
                'is_active' => true,
                'is_verified' => true,
                'registration_step' => 5,
                'registration_completed' => true,
            ],
            [
                'name' => 'Lisa Chen',
                'email' => 'interviewer2@rekrutpro.com',
                'password' => Hash::make('password'),
                'role_id' => $interviewerRole->id,
                'division_id' => $itDivision->id,
                'phone' => '081234567898',
                'is_active' => true,
                'is_verified' => true,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $this->repairLegacyCandidateProfile($user);
        }

        $this->command->info('Users seeded successfully!');
    }

    private function repairLegacyCandidateProfile(User $user): void
    {
        $legacyProfile = self::LEGACY_CANDIDATE_PROFILE_VALUES[$user->email] ?? null;

        if (!$legacyProfile) {
            return;
        }

        if (!$this->matchesLegacyCandidateProfile($user, $legacyProfile)) {
            return;
        }

        $user->update([
            'date_of_birth' => null,
            'address' => null,
            'education' => null,
            'experience' => null,
            'skills' => null,
            'linkedin_url' => null,
            'github_url' => null,
            'portfolio_url' => null,
        ]);
    }

    private function matchesLegacyCandidateProfile(User $user, array $legacyProfile): bool
    {
        return $user->address === $legacyProfile['address']
            && $user->education === $legacyProfile['education']
            && $user->experience === $legacyProfile['experience']
            && $user->skills === $legacyProfile['skills']
            && $user->date_of_birth?->toDateString() === $legacyProfile['date_of_birth'];
    }
}
