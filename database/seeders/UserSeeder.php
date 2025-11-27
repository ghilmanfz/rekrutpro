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
    /**
     * Run the database seeds.
     */
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
                'name' => 'Candidate Demo',
                'email' => 'candidate@example.com',
                'password' => Hash::make('password'),
                'role_id' => $candidateRole->id,
                'division_id' => null,
                'phone' => '081234567893',
                'is_active' => true,
                'is_verified' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
