<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Application;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditController extends Controller
{
    


    public function index(Request $request)
    {
         
        $stats = [
            'total_applications' => Application::count(),
            'screening_passed' => Application::where('status', 'screening_passed')->count(),
            'avg_hiring_time' => $this->calculateAverageHiringTime(),
        ];

         
        $query = AuditLog::with(['user', 'user.role']);

         
        if ($request->filled('activity')) {
            $searchTerm = $request->activity;
            $query->where(function($q) use ($searchTerm) {
                $q->where('action', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhere('new_values', 'like', '%' . $searchTerm . '%')
                  ->orWhere('old_values', 'like', '%' . $searchTerm . '%');
            });
        }

         
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

         
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(10);

         
        $users = User::whereHas('role', fn ($query) => $query->internal())
            ->orderBy('name')
            ->get();

        return view('superadmin.audit', compact('stats', 'auditLogs', 'users'));
    }

    


    private function calculateAverageHiringTime()
    {
        $hiredApplications = Application::where('status', 'hired')
            ->whereNotNull('updated_at')
            ->get();

        if ($hiredApplications->isEmpty()) {
            return '15 Hari';  
        }

        $totalDays = 0;
        $count = 0;

        foreach ($hiredApplications as $app) {
            $days = $app->created_at->diffInDays($app->updated_at);
            $totalDays += $days;
            $count++;
        }

        $average = $count > 0 ? round($totalDays / $count) : 15;

        return $average . ' Hari';
    }

    


    public function export(Request $request)
    {
        $auditLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'audit-logs-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($auditLogs) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Waktu',
                'Pengguna',
                'Aktivitas',
                'Model',
                'Model ID',
                'IP Address',
                'User Agent',
            ]);

            foreach ($auditLogs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->name,
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
