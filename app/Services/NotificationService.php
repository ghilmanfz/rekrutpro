<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    






    public function sendWhatsApp(string $event, string $phone, array $data): void
    {
         
        $template = NotificationTemplate::where('event', $event)
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
            Log::warning("NotificationService: No WhatsApp template found for event [{$event}]");
            return;
        }

        $apiKey = SystemConfig::get('whatsapp_api_key');
        if (!$apiKey) {
            Log::warning("NotificationService: WhatsApp API key not configured. Skipping event [{$event}]");
            return;
        }

        $message = $template->replacePlaceholders($data);

        Log::info("NotificationService: Sending WhatsApp for event [{$event}] to [{$phone}]");

        try {
            $response = Http::withHeaders(['Authorization' => $apiKey])
                ->post('https://api.fonnte.com/send', [
                    'target'   => $phone,
                    'message'  => $message,
                ]);

            Log::info("NotificationService: Fonnte API response for event [{$event}]", [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error("NotificationService: Failed to send WhatsApp for event [{$event}]: " . $e->getMessage());
        }
    }
}
