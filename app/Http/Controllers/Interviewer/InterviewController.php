<?php

namespace App\Http\Controllers\Interviewer;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    


    public function index(Request $request)
    {
        $interviewerId = auth()->id();

        $query = Interview::with([
            'application.candidate',
            'application.jobPosting.division',
            'assessment'
        ])
            ->where('interviewer_id', $interviewerId);

         
        if ($request->filled('search')) {
            $query->whereHas('application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

         
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

         
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        $interviews = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        return view('interviewer.interviews.index', compact('interviews'));
    }
}
