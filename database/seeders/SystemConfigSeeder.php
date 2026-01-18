<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // WhatsApp Configuration (Fonnte.com)
            [
                'key' => 'whatsapp_phone',
                'value' => '628123456789',
                'type' => 'string',
                'description' => 'Nomor WhatsApp untuk Fonnte API (format: 628xxx)',
            ],
            [
                'key' => 'whatsapp_api_key',
                'value' => '',
                'type' => 'string',
                'description' => 'API Key dari Fonnte.com',
            ],
            
            // Email Configuration
            [
                'key' => 'email_driver',
                'value' => 'smtp',
                'type' => 'string',
                'description' => 'Mail driver (smtp, sendmail, mailgun, ses)',
            ],
            [
                'key' => 'email_host',
                'value' => 'smtp.gmail.com',
                'type' => 'string',
                'description' => 'SMTP Host',
            ],
            [
                'key' => 'email_port',
                'value' => '587',
                'type' => 'string',
                'description' => 'SMTP Port',
            ],
            [
                'key' => 'email_username',
                'value' => '',
                'type' => 'string',
                'description' => 'SMTP Username/Email',
            ],
            [
                'key' => 'email_password',
                'value' => '',
                'type' => 'string',
                'description' => 'SMTP Password',
            ],
            [
                'key' => 'email_encryption',
                'value' => 'tls',
                'type' => 'string',
                'description' => 'Email Encryption (tls, ssl)',
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@rekrutpro.com',
                'type' => 'string',
                'description' => 'From Email Address',
            ],
            [
                'key' => 'email_from_name',
                'value' => 'RekrutPro',
                'type' => 'string',
                'description' => 'From Name',
            ],
        ];

        foreach ($configs as $config) {
            SystemConfig::create($config);
        }

        $this->command->info('System configurations seeded successfully!');
    }
}
