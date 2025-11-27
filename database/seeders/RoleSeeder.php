<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access - kelola user, master data, konfigurasi, dan laporan',
            ],
            [
                'name' => 'hr',
                'display_name' => 'HR / Recruiter',
                'description' => 'Kelola lowongan, proses kandidat dari awal sampai hired',
            ],
            [
                'name' => 'interviewer',
                'display_name' => 'Interviewer',
                'description' => 'Melakukan interview dan memberi penilaian kandidat',
            ],
            [
                'name' => 'candidate',
                'display_name' => 'Kandidat',
                'description' => 'Daftar, apply lowongan, dan cek status lamaran',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
