<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OfferSeeder extends Seeder
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

        $applications = Application::where('status', 'offered')->get();

        if ($applications->isEmpty()) {
            $this->command->warn('No applications with offered status found. Please run ApplicationSeeder first.');
            return;
        }

        $offers = [];

        // Offer untuk UI/UX Designer (status: pending - menunggu respon kandidat)
        if ($applications->count() > 0) {
            $app = $applications->first();
            
            $offers[] = [
                'application_id' => $app->id,
                'offered_by' => $hr->id,
                'position_title' => 'UI/UX Designer',
                'salary' => 10000000,
                'salary_currency' => 'IDR',
                'salary_period' => 'monthly',
                'benefits' => json_encode([
                    'Tunjangan kesehatan (BPJS Kesehatan & Ketenagakerjaan)',
                    'Asuransi swasta untuk karyawan dan keluarga',
                    'Laptop dan peralatan kerja',
                    'Flexible working hours',
                    'Work from home 2 hari per minggu',
                    'Annual leave 12 hari + cuti bersama',
                    'Learning & development budget Rp 5.000.000/tahun',
                    'Quarterly team building activities',
                    'Free snacks and beverages'
                ]),
                'contract_type' => 'Permanent',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => null,
                'terms_and_conditions' => "SYARAT DAN KETENTUAN PENAWARAN KERJA\n\n1. Masa Percobaan: 3 bulan dengan evaluasi di akhir periode\n2. Jam Kerja: Senin-Jumat, 09:00-18:00 (Flexible hours)\n3. Kenaikan Gaji: Review tahunan berdasarkan performa\n4. Bonus: Performance bonus tahunan (tergantung company performance)\n5. Penawaran ini berlaku selama 14 hari sejak tanggal penerbitan\n6. Kandidat diminta memberikan konfirmasi paling lambat sebelum tanggal expired\n7. Start date dapat dinegosiasikan sesuai ketersediaan kandidat\n8. Penawaran ini dapat dibatalkan jika kandidat tidak memberikan konfirmasi dalam waktu yang ditentukan",
                'internal_notes' => 'Kandidat sangat qualified dengan portfolio yang impressive. Salary Rp 10jt sesuai dengan range yang diminta dan competitive dengan market rate untuk 4 tahun pengalaman di UI/UX. Hasil interview: HR (90/100), Technical (95/100), Final (92/100). Strongly recommended.',
                'status' => 'pending',
                'valid_until' => Carbon::now()->addDays(14),
                'rejection_reason' => null,
                'responded_at' => null,
            ];
        }

        foreach ($offers as $offer) {
            Offer::create($offer);
        }

        $this->command->info('Offers seeded successfully!');
    }
}
