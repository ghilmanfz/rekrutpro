<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    


    public function run(): void
    {
        NotificationTemplate::where('type', 'email')
            ->orWhere('channel', 'email')
            ->delete();

        $templates = [
            [
                'name' => 'Application Submitted - WhatsApp',
                'slug' => 'application-submitted-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'application_submitted',
                'subject' => null,
                'body' => 'Halo {{nama}}, lamaran Anda untuk posisi {{posisi}} telah kami terima dengan kode {{kode_lamaran}}. Tim {{company_name}} akan meninjau lamaran Anda dan menghubungi Anda untuk tahap berikutnya.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'kode_lamaran', 'application_number', 'company_name']),
            ],
            [
                'name' => 'Screening Passed - WhatsApp',
                'slug' => 'screening-passed-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'screening_passed',
                'subject' => null,
                'body' => 'Halo {{nama}}, selamat Anda lolos tahap screening untuk posisi {{posisi}}. Tim {{company_name}} akan segera menghubungi Anda untuk jadwal interview.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Screening Rejected - WhatsApp',
                'slug' => 'screening-rejected-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'screening_rejected',
                'subject' => null,
                'body' => 'Halo {{nama}}, terima kasih atas minat Anda untuk posisi {{posisi}} di {{company_name}}. Saat ini kami memutuskan melanjutkan proses dengan kandidat lain. Semoga sukses untuk proses Anda berikutnya.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Interview Scheduled - WhatsApp',
                'slug' => 'interview-scheduled-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'interview_scheduled',
                'subject' => null,
                'body' => 'Halo {{nama}}, Anda dijadwalkan interview untuk posisi {{posisi}} pada {{tanggal}} pukul {{waktu}} di {{lokasi}}. Pewawancara: {{interviewer}}. Mohon konfirmasi kehadiran Anda.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'tanggal', 'interview_date', 'waktu', 'interview_time', 'lokasi', 'interview_location', 'interviewer', 'interviewer_name']),
            ],
            [
                'name' => 'Interview Reminder - WhatsApp',
                'slug' => 'interview-reminder-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'interview_reminder',
                'subject' => null,
                'body' => 'Halo {{nama}}, ini pengingat interview Anda untuk posisi {{posisi}} pada {{tanggal}} pukul {{waktu}} di {{lokasi}}. Mohon hadir 10 menit lebih awal.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'tanggal', 'interview_date', 'waktu', 'interview_time', 'lokasi', 'interview_location']),
            ],
            [
                'name' => 'Interview Passed - WhatsApp',
                'slug' => 'interview-passed-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'interview_passed',
                'subject' => null,
                'body' => 'Halo {{nama}}, selamat Anda lolos tahap interview untuk posisi {{posisi}}. Tim {{company_name}} akan segera menyampaikan langkah berikutnya.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Interview Rejected - WhatsApp',
                'slug' => 'interview-rejected-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'interview_rejected',
                'subject' => null,
                'body' => 'Halo {{nama}}, terima kasih telah mengikuti interview untuk posisi {{posisi}} di {{company_name}}. Saat ini kami memutuskan melanjutkan proses dengan kandidat lain. Semoga sukses untuk proses Anda berikutnya.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Job Offer Sent - WhatsApp',
                'slug' => 'offer-sent-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_sent',
                'subject' => null,
                'body' => 'Halo {{nama}}, selamat. Kami mengirimkan penawaran kerja untuk posisi {{posisi}} dengan penawaran gaji {{gaji}}. Silakan cek detailnya dan berikan konfirmasi Anda.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'gaji', 'salary_range', 'start_date', 'company_name']),
            ],
            [
                'name' => 'Offer Accepted - WhatsApp',
                'slug' => 'offer-accepted-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_accepted',
                'subject' => null,
                'body' => 'Halo {{nama}}, persetujuan Anda atas penawaran untuk posisi {{posisi}} sudah kami terima. Tim {{company_name}} akan memproses status diterima kerja dan menghubungi Anda untuk tahap berikutnya.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'start_date', 'company_name']),
            ],
            [
                'name' => 'Offer Negotiation Submitted - WhatsApp',
                'slug' => 'offer-negotiation-submitted-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_negotiation_submitted',
                'subject' => null,
                'body' => 'Halo {{nama}}, negosiasi gaji Anda untuk posisi {{posisi}} dengan nominal {{gaji_negosiasi}} telah kami terima. Tim {{company_name}} akan meninjau pengajuan Anda.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'kode_lamaran', 'application_number', 'gaji_awal', 'gaji_negosiasi', 'salary_range', 'company_name']),
            ],
            [
                'name' => 'Offer Negotiation Countered - WhatsApp',
                'slug' => 'offer-negotiation-countered-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_negotiation_countered',
                'subject' => null,
                'body' => 'Halo {{nama}}, negosiasi gaji Anda untuk posisi {{posisi}} belum dapat kami setujui. Tim {{company_name}} mengirimkan counter offer sebesar {{gaji_counter}}. Silakan cek dashboard kandidat untuk menerima atau mengajukan negosiasi lagi.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'kode_lamaran', 'application_number', 'gaji_awal', 'gaji_negosiasi', 'gaji_counter', 'salary_range', 'company_name']),
            ],
            [
                'name' => 'Offer Rejected - WhatsApp',
                'slug' => 'offer-rejected-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_rejected',
                'subject' => null,
                'body' => 'Halo {{nama}}, kami telah menerima keputusan Anda untuk menolak penawaran posisi {{posisi}}. Terima kasih telah mengikuti proses rekrutmen di {{company_name}}.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Offer Negotiation Approved - WhatsApp',
                'slug' => 'offer-negotiation-approved-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'offer_negotiation_approved',
                'subject' => null,
                'body' => 'Halo {{nama}}, selamat! Negosiasi gaji Anda untuk posisi {{posisi}} telah disetujui dengan gaji {{gaji_baru}}. Silakan buka dashboard kandidat untuk menerima penawaran secara resmi.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'kode_lamaran', 'application_number', 'gaji_awal', 'gaji_negosiasi', 'gaji_baru', 'salary_range', 'company_name']),
            ],
            [
                'name' => 'Application Hired - WhatsApp',
                'slug' => 'application-hired-whatsapp',
                'type' => 'whatsapp',
                'channel' => 'whatsapp',
                'event' => 'application_hired',
                'subject' => null,
                'body' => 'Halo {{nama}}, selamat datang di {{company_name}}! Anda resmi diterima sebagai karyawan untuk posisi {{posisi}} dengan gaji {{gaji}}. Tim HR akan segera menghubungi Anda untuk proses onboarding.',
                'available_placeholders' => json_encode(['nama', 'candidate_name', 'posisi', 'job_title', 'gaji', 'salary_range', 'start_date', 'company_name']),
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }

        $this->command->info('Notification templates seeded successfully!');
    }
}
