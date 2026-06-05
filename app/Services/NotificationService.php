<?php

namespace App\Services;

use App\Jobs\SendWhatsAppNotification;

class NotificationService
{
    /**
     * Antrekan pengiriman notifikasi WhatsApp.
     *
     * Pengiriman dilakukan lewat queue (App\Jobs\SendWhatsAppNotification)
     * agar request HTTP ke API Fonnte tidak memblokir respons ke pengguna.
     * Catatan: pastikan worker queue berjalan (php artisan queue:work),
     * jika tidak, job akan menumpuk di tabel "jobs" dan tidak terkirim.
     */
    public function sendWhatsApp(string $event, string $phone, array $data): void
    {
        SendWhatsAppNotification::dispatch($event, $phone, $data);
    }
}
