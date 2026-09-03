<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    






    public function sendWhatsApp(string $event, string $phone, array $data): bool
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
            return false;
        }

        $apiKey = SystemConfig::get('whatsapp_api_key');
        if (!$apiKey) {
            Log::warning("NotificationService: WhatsApp API key not configured. Skipping event [{$event}]");
            return false;
        }

        $message = $template->replacePlaceholders($data);
        $maskedPhone = $this->maskPhone($phone);

        Log::info("NotificationService: Sending WhatsApp for event [{$event}] to [{$maskedPhone}]");

        try {
            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => $apiKey])
                ->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                    // Simpan sebagai pending saat perangkat terputus agar notifikasi
                    // tidak langsung hilang dan dapat diproses setelah reconnect.
                    'connectOnly' => false,
                ]);

            $body = $response->json();
            $body = is_array($body) ? $body : [];
            $apiStatus = $body['status'] ?? $body['Status'] ?? null;

            if (! $response->successful() || $apiStatus === false) {
                Log::error("NotificationService: Fonnte rejected event [{$event}]", [
                    'http_status' => $response->status(),
                    'reason' => $body['reason'] ?? 'unknown',
                    'request_id' => $body['requestid'] ?? null,
                    'target' => $maskedPhone,
                ]);

                return false;
            }

            Log::info("NotificationService: Fonnte accepted event [{$event}]", [
                'http_status' => $response->status(),
                'process' => $body['process'] ?? null,
                'detail' => $body['detail'] ?? null,
                'request_id' => $body['requestid'] ?? null,
                'target' => $maskedPhone,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("NotificationService: Failed to send WhatsApp for event [{$event}]: " . $e->getMessage());

            return false;
        }
    }

    private function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        return strlen($digits) > 4
            ? str_repeat('*', strlen($digits) - 4).substr($digits, -4)
            : $digits;
    }
}
