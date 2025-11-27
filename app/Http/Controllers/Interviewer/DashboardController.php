<?php

namespace App\Http\Controllers\Interviewer;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Assessment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display interviewer dashboard
     */
    public function index()
    {
        $interviewerId = auth()->id();

        // Get statistics
        $stats = [
            'total' => Interview::where('interviewer_id', $interviewerId)->count(),
            'scheduled' => Interview::where('interviewer_id', $interviewerId)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', now())
                ->count(),
            'completed' => Interview::where('interviewer_id', $interviewerId)
                ->where('status', 'completed')
                ->count(),
        ];

        // Get upcoming interviews
        $upcomingInterviews = Interview::with([
            'application.candidate',
            'application.jobPosting.division'
        ])
            ->where('interviewer_id', $interviewerId)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        // Get recent assessments
        $recentAssessments = Assessment::whereHas('interview', function ($query) use ($interviewerId) {
            $query->where('interviewer_id', $interviewerId);
        })
            ->with(['interview.application.candidate', 'interview.application.jobPosting'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('interviewer.dashboard', compact('stats', 'upcomingInterviews', 'recentAssessments'));
    }
}
