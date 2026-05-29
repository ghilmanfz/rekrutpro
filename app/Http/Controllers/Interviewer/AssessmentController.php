<?php

namespace App\Http\Controllers\Interviewer;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Assessment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    


    public function index(Request $request)
    {
        $interviewerId = auth()->id();

        $query = Assessment::whereHas('interview', function ($query) use ($interviewerId) {
            $query->where('interviewer_id', $interviewerId);
        })
            ->with(['interview.application.candidate', 'interview.application.jobPosting']);

         
        if ($request->filled('search')) {
            $query->whereHas('interview.application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

         
        if ($request->filled('position')) {
            $query->whereHas('interview.application.jobPosting', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->position . '%');
            });
        }

         
        if ($request->filled('recommendation')) {
            $query->where('recommendation', $request->recommendation);
        }

        $assessments = $query->orderBy('created_at', 'desc')->paginate(15);

         
        $allAssessments = Assessment::whereHas('interview', function ($query) use ($interviewerId) {
            $query->where('interviewer_id', $interviewerId);
        })->get();

        $averageScore = $allAssessments->avg('overall_score') ?? 0;
        $recommendedCount = $allAssessments->whereIn('recommendation', ['sangat_direkomendasikan', 'direkomendasikan'])->count();

        return view('interviewer.assessments.index', compact('assessments', 'averageScore', 'recommendedCount'));
    }

    


    public function show($interviewId)
    {
        $interview = Interview::with([
            'application.candidate',
            'application.jobPosting.division',
            'application.jobPosting.location',
            'assessment'
        ])
            ->where('interviewer_id', auth()->id())
            ->findOrFail($interviewId);

        return view('interviewer.interviews.show', compact('interview'));
    }

    


    public function store(Request $request, $interviewId)
    {
        $interview = Interview::where('interviewer_id', auth()->id())
            ->findOrFail($interviewId);

         
        if ($interview->assessment) {
            return redirect()
                ->route('interviewer.interviews.show', $interviewId)
                ->with('error', 'Assessment sudah pernah dibuat untuk interview ini.');
        }

        $validated = $request->validate([
            'technical_score' => 'required|numeric|min:0|max:100',
            'communication_skill' => 'required|in:sangat_baik,baik,cukup,kurang',
            'problem_solving_score' => 'required|numeric|min:0|max:100',
            'teamwork_potential' => 'required|in:tinggi,sedang,rendah',
            'overall_score' => 'required|numeric|min:0|max:100',
            'recommendation' => 'required|in:sangat_direkomendasikan,direkomendasikan,tidak_direkomendasikan',
            'additional_notes' => 'required|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
        ]);

         
        $assessment = Assessment::create([
            'interview_id' => $interview->id,
            'interviewer_id' => auth()->id(),
            'technical_score' => $validated['technical_score'],
            'communication_skill' => $validated['communication_skill'],
            'problem_solving_score' => $validated['problem_solving_score'],
            'teamwork_potential' => $validated['teamwork_potential'],
            'overall_score' => $validated['overall_score'],
            'recommendation' => $validated['recommendation'],
            'additional_notes' => $validated['additional_notes'],
            'strengths' => $validated['strengths'] ?? null,
            'weaknesses' => $validated['weaknesses'] ?? null,
        ]);

         
        $interview->update(['status' => 'completed']);

         
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'assessment_created',
            'model_type' => 'Assessment',
            'model_id' => $assessment->id,
            'description' => 'Interviewer menyelesaikan penilaian untuk kandidat: ' . $interview->application->candidate->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

         

        return redirect()
            ->route('interviewer.interviews.show', $interviewId)
            ->with('success', 'Assessment berhasil disimpan!');
    }

    


    public function showAssessment($assessmentId)
    {
        $assessment = Assessment::with([
            'interview.application.candidate',
            'interview.application.jobPosting.division',
            'interview'
        ])
            ->whereHas('interview', function ($query) {
                $query->where('interviewer_id', auth()->id());
            })
            ->findOrFail($assessmentId);

        return view('interviewer.assessments.show', compact('assessment'));
    }
}
