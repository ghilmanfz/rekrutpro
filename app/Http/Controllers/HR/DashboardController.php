<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    


    public function index()
    {
         
        $stats = [
            'active_jobs' => JobPosting::active()->count(),
            'total_applications' => Application::count(),
            'pending_screening' => Application::where('status', 'submitted')->count(),
            'interview_scheduled' => Application::where('status', 'interview_scheduled')->count(),
            'offers_sent' => Application::where('status', 'offered')->count(),
            'hired' => Application::where('status', 'hired')->count(),
        ];

         
        $recentApplications = Application::with(['jobPosting', 'candidate'])
            ->latest()
            ->take(10)
            ->get();

         
        $upcomingInterviews = Interview::with(['application.candidate', 'interviewer'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->where('scheduled_at', '<=', now()->addDays(7))
            ->orderBy('scheduled_at')
            ->get();

         
        $activeJobs = JobPosting::with(['position', 'division', 'location'])
            ->active()
            ->withCount('applications')
            ->get();

        return view('hr.dashboard', compact(
            'stats',
            'recentApplications',
            'upcomingInterviews',
            'activeJobs'
        ));
    }
}
