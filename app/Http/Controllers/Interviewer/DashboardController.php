<?php

namespace App\Http\Controllers\Interviewer;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Assessment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    


    public function index()
    {
        $interviewerId = auth()->id();

         
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

         
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $interviewsThisWeek = Interview::where('interviewer_id', $interviewerId)
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$startOfWeek, $endOfWeek])
            ->count();

         
        $pendingAssessments = Interview::where('interviewer_id', $interviewerId)
            ->where('status', 'completed')
            ->whereDoesntHave('assessment')
            ->count();

         
        $stats = [
            'total' => Interview::where('interviewer_id', $interviewerId)->count(),
            'scheduled' => $interviewsThisWeek,
            'completed' => Interview::where('interviewer_id', $interviewerId)
                ->where('status', 'completed')
                ->count(),
            'pending_assessments' => $pendingAssessments,
        ];

         
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
