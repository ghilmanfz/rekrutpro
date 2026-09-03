<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConfigurationController extends Controller
{
    


    public function index()
    {
        $templates = NotificationTemplate::orderBy('event')->get();

         
        $whatsappPhone = SystemConfig::get('whatsapp_phone');
        $whatsappApiKeyConfigured = filled(SystemConfig::get('whatsapp_api_key'));

        return view('superadmin.config.index', compact(
            'templates',
            'whatsappPhone',
            'whatsappApiKeyConfigured'
        ));
    }

    


    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string|max:255',
            'channel' => 'required|in:email,whatsapp',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

         
        $exists = NotificationTemplate::where('event', $validated['event'])
            ->where(function ($q) use ($validated) {
                $q->where('type', $validated['channel'])
                  ->orWhere('channel', $validated['channel']);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'Template untuk event ini dengan channel yang sama sudah ada.');
        }

         
        $name = ucwords(str_replace('_', ' ', $validated['event'])) . ' - ' . ucfirst($validated['channel']);
        $slug = $validated['event'] . '-' . $validated['channel'];

        NotificationTemplate::create([
            'name' => $name,
            'slug' => $slug,
            'type' => $validated['channel'],      
            'channel' => $validated['channel'],   
            'event' => $validated['event'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Template notifikasi berhasil ditambahkan');
    }

    


    public function updateTemplate(Request $request, NotificationTemplate $template)
    {
        $validated = $request->validate([
            'event' => 'required|string|max:255',
            'channel' => 'required|in:email,whatsapp',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update([
            'event' => $validated['event'],
            'type' => $validated['channel'],      
            'channel' => $validated['channel'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Template notifikasi berhasil diperbarui');
    }

    


    public function destroyTemplate(NotificationTemplate $template)
    {
        $template->delete();

        return back()->with('success', 'Template notifikasi berhasil dihapus');
    }

    


    public function updateWhatsAppConfig(Request $request)
    {
        $currentApiKey = SystemConfig::get('whatsapp_api_key');

        $validated = $request->validate([
            'whatsapp_phone' => 'required|string|max:15|regex:/^628[0-9]{7,12}$/',
            'whatsapp_api_key' => [
                Rule::requiredIf(blank($currentApiKey)),
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'whatsapp_phone.regex' => 'Nomor WhatsApp harus berupa angka dengan format 628xxx.',
        ]);

        SystemConfig::set('whatsapp_phone', $validated['whatsapp_phone'], 'string', 'Nomor WhatsApp untuk Fonnte API');

        if (filled($validated['whatsapp_api_key'] ?? null)) {
            SystemConfig::set('whatsapp_api_key', $validated['whatsapp_api_key'], 'string', 'API Key dari Fonnte.com');
        }

        return back()->with('success', 'Konfigurasi WhatsApp berhasil diperbarui');
    }
}
