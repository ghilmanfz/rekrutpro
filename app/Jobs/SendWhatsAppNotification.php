<?php

namespace App\Jobs;

use App\Models\NotificationTemplate;
use App\Models\SystemConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan ulang jika pengiriman gagal.
     */
    public int $tries = 3;

    /**
     * Jeda (detik) antar percobaan ulang.
     */
    public int $backoff = 10;

    public function __construct(
        public string $event,
        public string $phone,
        public array $data
    ) {}

    public function handle(): void
    {
        $template = NotificationTemplate::where('event', $this->event)
            ->where(function ($query) {
                $query->where('type', 'whatsapp')
                      ->orWhere('channel', 'whatsapp');
            })
            ->where(function ($query) {
                $query->where('is_active', true)
                      ->orWhereNull('is_active');
            })
            ->first();

        if (!$template) {
            Log::warning("SendWhatsAppNotification: No WhatsApp template found for event [{$this->event}]");
            return;
        }

        $apiKey = SystemConfig::get('whatsapp_api_key');
        if (!$apiKey) {
            Log::warning("SendWhatsAppNotification: WhatsApp API key not configured. Skipping event [{$this->event}]");
            return;
        }

        $message = $template->replacePlaceholders($this->data);

        Log::info("SendWhatsAppNotification: Sending WhatsApp for event [{$this->event}] to [{$this->phone}]");

        $response = Http::withHeaders(['Authorization' => $apiKey])
            ->connectTimeout(5)
            ->timeout(15)
            ->post('https://api.fonnte.com/send', [
                'target'  => $this->phone,
                'message' => $message,
            ]);

        Log::info("SendWhatsAppNotification: Fonnte API response for event [{$this->event}]", [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);
    }

    /**
     * Dipanggil saat seluruh percobaan ulang habis.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendWhatsAppNotification: Failed to send WhatsApp for event [{$this->event}]: " . $exception->getMessage());
    }
}
