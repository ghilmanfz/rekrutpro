<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $templates = [
            [
                'name' => 'Offer Negotiation Submitted - WhatsApp',
                'slug' => 'offer-negotiation-submitted-whatsapp',
                'event' => 'offer_negotiation_submitted',
                'body' => 'Halo {{nama}}, negosiasi gaji Anda untuk posisi {{posisi}} dengan nominal {{gaji_negosiasi}} telah kami terima. Tim {{company_name}} akan meninjau pengajuan Anda.',
            ],
            [
                'name' => 'Offer Negotiation Countered - WhatsApp',
                'slug' => 'offer-negotiation-countered-whatsapp',
                'event' => 'offer_negotiation_countered',
                'body' => 'Halo {{nama}}, negosiasi gaji Anda untuk posisi {{posisi}} belum dapat kami setujui. Tim {{company_name}} mengirimkan counter offer sebesar {{gaji_counter}}. Silakan cek dashboard kandidat untuk menerima atau mengajukan negosiasi lagi.',
            ],
        ];

        foreach ($templates as $template) {
            DB::table('notification_templates')->updateOrInsert(
                ['slug' => $template['slug']],
                [
                    'name' => $template['name'],
                    'type' => 'whatsapp',
                    'channel' => 'whatsapp',
                    'event' => $template['event'],
                    'subject' => null,
                    'body' => $template['body'],
                    'available_placeholders' => json_encode([
                        'nama',
                        'candidate_name',
                        'posisi',
                        'job_title',
                        'kode_lamaran',
                        'application_number',
                        'gaji_awal',
                        'gaji_negosiasi',
                        'gaji_counter',
                        'salary_range',
                        'company_name',
                    ]),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        DB::table('notification_templates')
            ->where('slug', 'offer-accepted-whatsapp')
            ->update([
                'body' => 'Halo {{nama}}, persetujuan Anda atas penawaran untuk posisi {{posisi}} sudah kami terima. Tim {{company_name}} akan memproses status diterima kerja dan menghubungi Anda untuk tahap berikutnya.',
                'updated_at' => $now,
            ]);
    }

    public function down(): void
    {
        DB::table('notification_templates')
            ->whereIn('slug', [
                'offer-negotiation-submitted-whatsapp',
                'offer-negotiation-countered-whatsapp',
            ])
            ->delete();

        DB::table('notification_templates')
            ->where('slug', 'offer-accepted-whatsapp')
            ->update([
                'body' => 'Halo {{nama}}, terima kasih telah menerima penawaran untuk posisi {{posisi}}. Tim {{company_name}} akan segera menghubungi Anda untuk proses onboarding.',
                'updated_at' => now(),
            ]);
    }
};
