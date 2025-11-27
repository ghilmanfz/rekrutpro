<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\Position;
use App\Models\Location;
use App\Models\NotificationTemplate;
use App\Models\JobPosting;
use App\Models\Application;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display super admin dashboard
     */
    public function index()
    {
        // Get user statistics by role
        $superAdminCount = User::where('role_id', 1)->count();
        $hrCount = User::where('role_id', 2)->count();
        $interviewerCount = User::where('role_id', 3)->count();
        $candidateCount = User::where('role_id', 4)->count();

        // Get master data statistics
        $divisionCount = Division::count();
        $positionCount = Position::count();
        $locationCount = Location::count();
        $templateCount = NotificationTemplate::count();

        // Get recruitment statistics
        $activeJobsCount = JobPosting::where('status', 'active')->count();
        $totalApplicationsCount = Application::count();
        $pendingApplicationsCount = Application::whereIn('status', ['submitted', 'screening_passed', 'interview_scheduled'])->count();

        return view('superadmin.dashboard', compact(
            'superAdminCount',
            'hrCount',
            'interviewerCount',
            'candidateCount',
            'divisionCount',
            'positionCount',
            'locationCount',
            'templateCount',
            'activeJobsCount',
            'totalApplicationsCount',
            'pendingApplicationsCount'
        ));
    }
}
