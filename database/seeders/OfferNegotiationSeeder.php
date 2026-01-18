<?php

namespace Database\Seeders;

use App\Models\OfferNegotiation;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OfferNegotiationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = Offer::all();
        
        if ($offers->isEmpty()) {
            $this->command->warn('No offers found. Please run OfferSeeder first.');
            return;
        }

        $hrRole = \App\Models\Role::where('name', 'hr')->first();
        $hr = User::where('role_id', $hrRole->id)->first();

        // Untuk demo, kita buat beberapa skenario negosiasi
        $negotiations = [];

        // Negosiasi untuk offer pertama (UI/UX Designer) - Status: Pending
        if ($offers->count() > 0) {
            $offer = $offers->first();
            $candidate = $offer->application->candidate;

            $negotiations[] = [
                'offer_id' => $offer->id,
                'candidate_id' => $candidate->id,
                'proposed_salary' => 12000000,
                'candidate_notes' => "Terima kasih atas penawaran yang diberikan. Setelah saya pertimbangkan, saya ingin mengajukan negosiasi gaji menjadi Rp 12.000.000 berdasarkan:\n\n1. Pengalaman saya 2+ tahun sebagai UI/UX Designer dengan portfolio yang proven\n2. Skills tambahan saya di motion design dan user research\n3. Market rate untuk posisi serupa di Jakarta berkisar Rp 11-14 juta\n4. Saya akan membawa value tambahan dengan kemampuan mentoring untuk junior designers\n\nSaya sangat excited untuk bergabung dengan tim dan berkontribusi maksimal. Terima kasih atas pertimbangannya.",
                'status' => 'pending',
                'created_at' => Carbon::now()->subHours(6),
            ];
        }

        foreach ($negotiations as $negotiation) {
            OfferNegotiation::create($negotiation);
        }

        $this->command->info('Offer Negotiations seeded successfully!');
    }
}
