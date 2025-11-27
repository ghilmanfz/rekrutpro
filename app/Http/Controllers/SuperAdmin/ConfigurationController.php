<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display configuration page
     */
    public function index()
    {
        $templates = NotificationTemplate::orderBy('event')->get();

        return view('superadmin.config.index', compact('templates'));
    }

    /**
     * Store new notification template
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'event' => 'required|string|max:255|unique:notification_templates',
            'channel' => 'required|in:email,whatsapp,sms',
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
            'channel' => 'required|in:email,whatsapp,sms',
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
}
