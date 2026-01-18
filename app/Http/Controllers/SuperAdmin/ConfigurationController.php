<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\SystemConfig;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display configuration page
     */
    public function index()
    {
        $templates = NotificationTemplate::orderBy('event')->get();

        // Get system configurations
        $whatsappPhone = SystemConfig::get('whatsapp_phone');
        $whatsappApiKey = SystemConfig::get('whatsapp_api_key');
        
        $emailDriver = SystemConfig::get('email_driver', 'smtp');
        $emailHost = SystemConfig::get('email_host');
        $emailPort = SystemConfig::get('email_port', 587);
        $emailUsername = SystemConfig::get('email_username');
        $emailPassword = SystemConfig::get('email_password');
        $emailEncryption = SystemConfig::get('email_encryption', 'tls');
        $emailFromAddress = SystemConfig::get('email_from_address');
        $emailFromName = SystemConfig::get('email_from_name');

        return view('superadmin.config.index', compact(
            'templates',
            'whatsappPhone',
            'whatsappApiKey',
            'emailDriver',
            'emailHost',
            'emailPort',
            'emailUsername',
            'emailPassword',
            'emailEncryption',
            'emailFromAddress',
            'emailFromName'
        ));
    }

    /**
     * Store new notification template
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string|max:255|unique:notification_templates',
            'channel' => 'required|in:email,whatsapp',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        NotificationTemplate::create($validated);

        return back()->with('success', 'Template notifikasi berhasil ditambahkan');
    }

    /**
     * Update notification template
     */
    public function updateTemplate(Request $request, NotificationTemplate $template)
    {
        $validated = $request->validate([
            'event' => 'required|string|max:255|unique:notification_templates,event,' . $template->id,
            'channel' => 'required|in:email,whatsapp',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($validated);

        return back()->with('success', 'Template notifikasi berhasil diperbarui');
    }

    /**
     * Delete notification template
     */
    public function destroyTemplate(NotificationTemplate $template)
    {
        $template->delete();

        return back()->with('success', 'Template notifikasi berhasil dihapus');
    }

    /**
     * Update WhatsApp configuration
     */
    public function updateWhatsAppConfig(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_phone' => 'required|string|max:20',
            'whatsapp_api_key' => 'required|string|max:255',
        ]);

        SystemConfig::set('whatsapp_phone', $validated['whatsapp_phone'], 'string', 'Nomor WhatsApp untuk Fonnte API');
        SystemConfig::set('whatsapp_api_key', $validated['whatsapp_api_key'], 'string', 'API Key dari Fonnte.com');

        return back()->with('success', 'Konfigurasi WhatsApp berhasil diperbarui');
    }

    /**
     * Update Email configuration
     */
    public function updateEmailConfig(Request $request)
    {
        $validated = $request->validate([
            'email_driver' => 'required|in:smtp,sendmail,mailgun,ses',
            'email_host' => 'required|string|max:255',
            'email_port' => 'required|integer',
            'email_username' => 'required|string|max:255',
            'email_password' => 'required|string|max:255',
            'email_encryption' => 'required|in:tls,ssl',
            'email_from_address' => 'required|email|max:255',
            'email_from_name' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            SystemConfig::set($key, $value, 'string');
        }

        return back()->with('success', 'Konfigurasi Email berhasil diperbarui');
    }
}
