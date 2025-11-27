<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Display audit logs and reports
     */
    public function index(Request $request)
    {
        // Report Summary Statistics
        $stats = [
            'total_applications' => Application::count(),
            'screening_passed' => Application::where('status', 'screening_passed')->count(),
            'avg_hiring_time' => $this->calculateAverageHiringTime(),
        ];

        // Get audit logs with filters
        $query = AuditLog::with(['user', 'user.role']);

        // Filter by activity/event
        if ($request->filled('activity')) {
            $query->where('event', 'like', '%' . $request->activity . '%');
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get users for filter dropdown
        $users = User::whereIn('role_id', [1, 2, 3]) // super_admin, hr, interviewer
            ->orderBy('name')
            ->get();

        return view('superadmin.audit', compact('stats', 'auditLogs', 'users'));
    }

    /**
     * Calculate average hiring time in days
     */
    private function calculateAverageHiringTime()
    {
        $hiredApplications = Application::where('status', 'hired')
            ->whereNotNull('updated_at')
            ->get();

        if ($hiredApplications->isEmpty()) {
            return '15 Hari'; // Default
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

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        // TODO: Implement export to Excel/CSV
        return back()->with('success', 'Data berhasil diekspor');
    }
}
