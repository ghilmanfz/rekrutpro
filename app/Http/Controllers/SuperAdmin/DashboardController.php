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
    


    public function index()
    {
         
        $superAdminCount = User::withRoleName(Role::SUPER_ADMIN)->count();
        $hrCount = User::withRoleName(Role::HR)->count();
        $interviewerCount = User::withRoleName(Role::INTERVIEWER)->count();
        $candidateCount = User::withRoleName(Role::CANDIDATE)->count();

         
        $divisionCount = Division::count();
        $positionCount = Position::count();
        $locationCount = Location::count();
        $templateCount = NotificationTemplate::count();

         
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
