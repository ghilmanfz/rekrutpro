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
                'name' => 'Application Interview Scheduled - WhatsApp',
                'slug' => 'application-interview-scheduled-whatsapp',
                'event' => 'application_interview_scheduled',
                'body' => 'Halo {{nama}}, status lamaran Anda untuk posisi {{posisi}} telah diperbarui menjadi Terjadwal Interview. Tim {{company_name}} akan menghubungi Anda untuk detail jadwal interview.',
            ],
            [
                'name' => 'Application Offered - WhatsApp',
                'slug' => 'application-offered-whatsapp',
                'event' => 'application_offered',
                'body' => 'Halo {{nama}}, selamat. Status lamaran Anda untuk posisi {{posisi}} telah diperbarui menjadi Ditawarkan. Silakan cek dashboard kandidat atau tunggu informasi lanjutan dari tim {{company_name}}.',
            ],
            [
                'name' => 'Application Hired - WhatsApp',
                'slug' => 'application-hired-whatsapp',
                'event' => 'application_hired',
                'body' => 'Halo {{nama}}, selamat Anda diterima kerja untuk posisi {{posisi}}. Tim {{company_name}} akan menghubungi Anda untuk proses berikutnya.',
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
                        'status',
                        'company_name',
                    ]),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('notification_templates')
            ->whereIn('slug', [
                'application-interview-scheduled-whatsapp',
                'application-offered-whatsapp',
                'application-hired-whatsapp',
            ])
            ->delete();
    }
};
