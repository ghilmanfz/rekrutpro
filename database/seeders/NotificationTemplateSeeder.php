<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Application Submitted - Email',
                'slug' => 'application-submitted-email',
                'type' => 'email',
                'event' => 'application_submitted',
                'subject' => 'Aplikasi Anda Telah Diterima - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nTerima kasih telah melamar posisi {{job_title}} di {{company_name}}.\n\nAplikasi Anda telah kami terima dan sedang dalam proses review oleh tim kami. Kami akan menghubungi Anda dalam 3-5 hari kerja untuk informasi selanjutnya.\n\nNomor Aplikasi: {{application_number}}\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'company_name', 'application_number']),
            ],
            [
                'name' => 'Application Submitted - WhatsApp',
                'slug' => 'application-submitted-whatsapp',
                'type' => 'whatsapp',
                'event' => 'application_submitted',
                'subject' => null,
                'body' => 'Halo {{candidate_name}}, aplikasi Anda untuk posisi {{job_title}} telah diterima. Nomor aplikasi: {{application_number}}. Tim kami akan menghubungi Anda segera.',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'application_number']),
            ],
            [
                'name' => 'Screening Passed - Email',
                'slug' => 'screening-passed-email',
                'type' => 'email',
                'event' => 'screening_passed',
                'subject' => 'Selamat! Anda Lolos Tahap Screening - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nSelamat! Kami dengan senang hati memberitahukan bahwa Anda telah lolos tahap screening untuk posisi {{job_title}}.\n\nTahap selanjutnya adalah wawancara dengan tim kami. Kami akan menghubungi Anda segera untuk penjadwalan wawancara.\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Screening Rejected - Email',
                'slug' => 'screening-rejected-email',
                'type' => 'email',
                'event' => 'screening_rejected',
                'subject' => 'Update Aplikasi Anda - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nTerima kasih atas minat Anda untuk bergabung dengan {{company_name}} sebagai {{job_title}}.\n\nSetelah melakukan review yang cermat, kami memutuskan untuk melanjutkan dengan kandidat lain yang lebih sesuai dengan kebutuhan saat ini.\n\nKami menghargai waktu dan usaha Anda dalam proses aplikasi ini. Kami mendorong Anda untuk terus memantau lowongan kami di masa depan.\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'company_name']),
            ],
            [
                'name' => 'Interview Scheduled - Email',
                'slug' => 'interview-scheduled-email',
                'type' => 'email',
                'event' => 'interview_scheduled',
                'subject' => 'Undangan Wawancara - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nKami mengundang Anda untuk wawancara posisi {{job_title}}.\n\nDetail Wawancara:\n- Tanggal: {{interview_date}}\n- Waktu: {{interview_time}}\n- Lokasi: {{interview_location}}\n- Pewawancara: {{interviewer_name}}\n- Tipe: {{interview_type}}\n\nMohon konfirmasi kehadiran Anda paling lambat 1 hari sebelum jadwal wawancara.\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'interview_date', 'interview_time', 'interview_location', 'interviewer_name', 'interview_type', 'company_name']),
            ],
            [
                'name' => 'Interview Scheduled - WhatsApp',
                'slug' => 'interview-scheduled-whatsapp',
                'type' => 'whatsapp',
                'event' => 'interview_scheduled',
                'subject' => null,
                'body' => 'Halo {{candidate_name}}, Anda dijadwalkan wawancara untuk posisi {{job_title}} pada {{interview_date}} pukul {{interview_time}}. Lokasi: {{interview_location}}. Mohon konfirmasi kehadiran.',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'interview_date', 'interview_time', 'interview_location']),
            ],
            [
                'name' => 'Interview Reminder - Email',
                'slug' => 'interview-reminder-email',
                'type' => 'email',
                'event' => 'interview_reminder',
                'subject' => 'Pengingat Wawancara Besok - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nIni adalah pengingat untuk wawancara Anda besok:\n\n- Posisi: {{job_title}}\n- Tanggal: {{interview_date}}\n- Waktu: {{interview_time}}\n- Lokasi: {{interview_location}}\n- Pewawancara: {{interviewer_name}}\n\nPastikan Anda datang 10 menit lebih awal.\n\nSampai jumpa!\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'interview_date', 'interview_time', 'interview_location', 'interviewer_name', 'company_name']),
            ],
            [
                'name' => 'Interview Passed - Email',
                'slug' => 'interview-passed-email',
                'type' => 'email',
                'event' => 'interview_passed',
                'subject' => 'Selamat! Anda Lolos Wawancara - {{job_title}}',
                'body' => 'Halo {{candidate_name}},\n\nSelamat! Anda telah berhasil melewati tahap wawancara untuk posisi {{job_title}}.\n\n{{next_steps}}\n\nTerima kasih atas partisipasi Anda.\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'next_steps', 'company_name']),
            ],
            [
                'name' => 'Job Offer Sent - Email',
                'slug' => 'offer-sent-email',
                'type' => 'email',
                'event' => 'offer_sent',
                'subject' => 'Penawaran Kerja - {{job_title}} di {{company_name}}',
                'body' => 'Halo {{candidate_name}},\n\nSelamat! Kami dengan senang hati menawarkan Anda posisi {{job_title}} di {{company_name}}.\n\nDetail Penawaran:\n- Posisi: {{job_title}}\n- Gaji: {{salary_range}}\n- Tanggal Mulai: {{start_date}}\n- Lokasi: {{location}}\n\nSurat penawaran lengkap terlampir. Mohon berikan konfirmasi penerimaan dalam 7 hari kerja.\n\nKami sangat antusias menyambut Anda di tim kami!\n\nSalam,\n{{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'company_name', 'salary_range', 'start_date', 'location']),
            ],
            [
                'name' => 'Offer Accepted - Email',
                'slug' => 'offer-accepted-email',
                'type' => 'email',
                'event' => 'offer_accepted',
                'subject' => 'Terima Kasih - Selamat Datang di {{company_name}}!',
                'body' => 'Halo {{candidate_name}},\n\nTerima kasih telah menerima penawaran kami!\n\nKami sangat senang Anda akan bergabung sebagai {{job_title}} mulai {{start_date}}.\n\nTim HR kami akan menghubungi Anda segera untuk proses onboarding dan persiapan hari pertama Anda.\n\nSelamat datang di keluarga {{company_name}}!\n\nSalam,\nTim HR {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'start_date', 'company_name']),
            ],
            [
                'name' => 'Offer Rejected - Email',
                'slug' => 'offer-rejected-email',
                'type' => 'email',
                'event' => 'offer_rejected',
                'subject' => 'Terima Kasih atas Waktu Anda',
                'body' => 'Halo {{candidate_name}},\n\nKami telah menerima pemberitahuan bahwa Anda memutuskan untuk menolak penawaran kami untuk posisi {{job_title}}.\n\nKami menghargai waktu dan usaha Anda selama proses rekrutmen ini. Kami berharap jalan terbaik untuk karir Anda ke depan.\n\nJika di masa depan Anda tertarik untuk bergabung dengan {{company_name}}, kami akan dengan senang hati mempertimbangkan aplikasi Anda.\n\nSalam,\nTim Rekrutmen {{company_name}}',
                'available_placeholders' => json_encode(['candidate_name', 'job_title', 'company_name']),
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::create($template);
        }

        $this->command->info('Notification templates seeded successfully!');
    }
}
