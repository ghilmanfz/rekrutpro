<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display candidate dashboard
     */
    public function index()
    {
        $candidateId = auth()->id();

        // Get all applications with relationships
        $applications = Application::with(['jobPosting.division', 'jobPosting.position'])
            ->where('candidate_id', $candidateId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $stats = [
            'total' => Application::where('candidate_id', $candidateId)->count(),
            'submitted' => Application::where('candidate_id', $candidateId)
                ->where('status', 'submitted')
                ->count(),
            'interview' => Application::where('candidate_id', $candidateId)
                ->whereIn('status', ['interview_scheduled', 'interview_passed'])
                ->count(),
            'success' => Application::where('candidate_id', $candidateId)
                ->whereIn('status', ['offered', 'hired'])
                ->count(),
        ];

        // Get upcoming interviews
        $upcomingInterviews = Interview::whereHas('application', function ($query) use ($candidateId) {
            $query->where('candidate_id', $candidateId);
        })
            ->with(['application.jobPosting.division'])
            ->where('status', 'scheduled')
            ->where('interview_date', '>=', now())
            ->orderBy('interview_date')
            ->orderBy('interview_time')
            ->limit(5)
            ->get();

        return view('candidate.dashboard', compact('applications', 'stats', 'upcomingInterviews'));
    }
}
