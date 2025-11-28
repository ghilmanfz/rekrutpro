<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show all notifications
     */
    public function index()
    {
        // TODO: Implement actual notification system
        $notifications = [
            [
                'id' => 1,
                'icon' => 'check-circle',
                'color' => 'green',
                'title' => 'Lamaran Anda untuk posisi Software Engineer telah diterima',
                'description' => 'Tim HR akan meninjau lamaran Anda dalam 2-3 hari kerja.',
                'time' => '2 jam yang lalu',
                'read' => false
            ],
            [
                'id' => 2,
                'icon' => 'calendar',
                'color' => 'blue',
                'title' => 'Jadwal interview untuk posisi Project Manager',
                'description' => 'Interview dijadwalkan pada tanggal 15 Januari 2025 pukul 10:00 WIB.',
                'time' => '5 jam yang lalu',
                'read' => false
            ],
            [
                'id' => 3,
                'icon' => 'file-alt',
                'color' => 'yellow',
                'title' => 'Dokumen Anda sedang direview',
                'description' => 'Tim HR sedang meninjau dokumen lamaran Anda.',
                'time' => '1 hari yang lalu',
                'read' => true
            ],
            [
                'id' => 4,
                'icon' => 'info-circle',
                'color' => 'gray',
                'title' => 'Pembaruan sistem: Fitur baru telah ditambahkan',
                'description' => 'Sekarang Anda dapat melihat status lamaran secara real-time.',
                'time' => '2 hari yang lalu',
                'read' => true
            ],
            [
                'id' => 5,
                'icon' => 'exclamation-triangle',
                'color' => 'red',
                'title' => 'Lamaran untuk Data Analyst memerlukan dokumen tambahan',
                'description' => 'Silakan upload ijazah terakhir Anda untuk melengkapi aplikasi.',
                'time' => '3 hari yang lalu',
                'read' => false
            ],
            [
                'id' => 6,
                'icon' => 'gift',
                'color' => 'purple',
                'title' => 'Selamat! Anda menerima penawaran kerja',
                'description' => 'Silakan cek email Anda untuk detail penawaran posisi UI/UX Designer.',
                'time' => '4 hari yang lalu',
                'read' => true
            ],
        ];

        $unreadCount = collect($notifications)->where('read', false)->count();
        $readCount = collect($notifications)->where('read', true)->count();

        return view('candidate.notifications', compact('notifications', 'unreadCount', 'readCount'));
    }
}
