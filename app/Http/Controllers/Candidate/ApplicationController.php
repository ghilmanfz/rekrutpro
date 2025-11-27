<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\AuditLog;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Show the application form for a specific job
     */
    public function create($jobId)
    {
        $job = JobPosting::with(['division', 'position', 'location'])
            ->where('status', 'active')
            ->findOrFail($jobId);

        // Check if user already applied for this job
        $existingApplication = Application::where('candidate_id', auth()->id())
            ->where('job_posting_id', $jobId)
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('candidate.applications.show', $existingApplication->id)
                ->with('info', 'Anda sudah melamar untuk posisi ini.');
        }

        return view('candidate.applications.create', compact('job'));
    }

    /**
     * Store a new application
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'education' => 'required|in:SMA/SMK,D3,S1,S2,S3',
            'experience' => 'required|string',
            'expected_salary' => 'required|numeric|min:0',
            'availability' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'portfolio' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'agree_terms' => 'required|accepted',
        ]);

        // Check if already applied
        $existingApplication = Application::where('candidate_id', auth()->id())
            ->where('job_posting_id', $request->job_posting_id)
            ->first();

        if ($existingApplication) {
            return redirect()
                ->route('candidate.applications.show', $existingApplication->id)
                ->with('info', 'Anda sudah melamar untuk posisi ini.');
        }

        // Upload CV
        $cvPath = $this->fileUploadService->uploadCV(
            $request->file('cv'),
            $validated['full_name']
        );

        // Upload Portfolio (optional)
        $portfolioPath = null;
        if ($request->hasFile('portfolio')) {
            $portfolioPath = $this->fileUploadService->uploadPortfolio(
                $request->file('portfolio'),
                $validated['full_name']
            );
        }

        // Generate application code
        $applicationCode = 'APP-' . strtoupper(Str::random(8));

        // Create application
        $application = Application::create([
            'candidate_id' => auth()->id(),
            'job_posting_id' => $validated['job_posting_id'],
            'application_code' => $applicationCode,
            'education' => $validated['education'],
            'experience' => $validated['experience'],
            'expected_salary' => $validated['expected_salary'],
            'availability' => $validated['availability'],
            'cv_path' => $cvPath,
            'portfolio_path' => $portfolioPath,
            'cover_letter' => $validated['cover_letter'],
            'status' => 'submitted',
        ]);

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'application_submitted',
            'model_type' => 'Application',
            'model_id' => $application->id,
            'description' => 'Kandidat mengirim lamaran untuk posisi: ' . $application->jobPosting->title,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // TODO: Send notification to candidate and HR

        return redirect()
            ->route('candidate.applications.show', $application->id)
            ->with('success', 'Lamaran Anda berhasil dikirim! Kode lamaran: ' . $applicationCode);
    }

    /**
     * Show candidate's application detail
     */
    public function show($id)
    {
        $application = Application::with([
            'candidate',
            'jobPosting.division',
            'jobPosting.location',
            'interviews',
            'offers'
        ])
            ->where('candidate_id', auth()->id())
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }
}
