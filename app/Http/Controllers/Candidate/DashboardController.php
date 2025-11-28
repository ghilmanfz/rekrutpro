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
            'rejected' => Application::where('candidate_id', $candidateId)
                ->where('status', 'like', 'rejected%')
                ->count(),
        ];

        // Get upcoming interviews
        $upcomingInterviews = Interview::whereHas('application', function ($query) use ($candidateId) {
            $query->where('candidate_id', $candidateId);
        })
            ->with(['application.jobPosting'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        // Calculate profile completion
        $user = auth()->user();
        $profileFields = [
            'name', 'email', 'phone', 'address', 
            'education', 'experience', 'skills'
        ];
        
        $completedFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompletion = round(($completedFields / count($profileFields)) * 100);

        // Get recent notifications (sample data - replace with actual notification system later)
        $notifications = [
            [
                'icon' => 'check-circle',
                'color' => 'green',
                'title' => 'Lamaran Anda untuk posisi Software Engineer telah diterima',
                'time' => '2 jam yang lalu'
            ],
            [
                'icon' => 'calendar',
                'color' => 'blue',
                'title' => 'Jadwal interview untuk posisi Project Manager',
                'time' => '5 jam yang lalu'
            ],
            [
                'icon' => 'file-alt',
                'color' => 'yellow',
                'title' => 'Dokumen Anda sedang direview',
                'time' => '1 hari yang lalu'
            ],
            [
                'icon' => 'info-circle',
                'color' => 'gray',
                'title' => 'Pembaruan sistem: Fitur baru telah ditambahkan',
                'time' => '2 hari yang lalu'
            ],
        ];

        return view('candidate.dashboard', compact('applications', 'stats', 'upcomingInterviews', 'profileCompletion', 'notifications'));
    }
}
