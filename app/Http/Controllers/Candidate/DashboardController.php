<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Services\CandidateNotificationFeed;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private CandidateNotificationFeed $notificationFeed)
    {
    }

    


    public function index()
    {
        $candidateId = auth()->id();

         
        $applications = Application::with(['jobPosting.division', 'jobPosting.position'])
            ->where('candidate_id', $candidateId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

         
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
            'rejected' => Application::where('candidate_id', $candidateId)
                ->where('status', 'like', 'rejected%')
                ->count(),
        ];

         
        $upcomingInterviews = Interview::whereHas('application', function ($query) use ($candidateId) {
            $query->where('candidate_id', $candidateId);
        })
            ->with(['application.jobPosting'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

         
        $user = auth()->user();
        $profileFields = [
            'name', 'email', 'phone', 'date_of_birth', 'address',
            'education', 'study_program'
        ];
        
        $completedFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompletion = round(($completedFields / count($profileFields)) * 100);

        $notifications = $this->notificationFeed->forUser($user, 4);

        return view('candidate.dashboard', compact('applications', 'stats', 'upcomingInterviews', 'profileCompletion', 'notifications'));
    }
}
