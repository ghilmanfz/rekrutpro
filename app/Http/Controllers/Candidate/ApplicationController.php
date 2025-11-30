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
     * Show all applications from candidate
     */
    public function index()
    {
        $applications = Application::with(['jobPosting.division', 'jobPosting.position'])
            ->where('candidate_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('candidate.applications.index', compact('applications'));
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
        // Note: We don't validate personal data fields because they come from user profile
        // and are stored in snapshot automatically
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
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

        $user = auth()->user();

        try {
            // Upload CV
            $cvPath = $this->fileUploadService->uploadCV(
                $request->file('cv'),
                $user->full_name ?? $user->name
            );

            // Upload Portfolio (optional)
            $portfolioPath = null;
            if ($request->hasFile('portfolio')) {
                $portfolioPath = $this->fileUploadService->uploadPortfolio(
                    $request->file('portfolio'),
                    $user->full_name ?? $user->name
                );
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }

        // Generate application code (UNIQUE)
        $applicationCode = 'APP-' . strtoupper(Str::random(8));
        
        // Generate unique code for database (required field)
        $uniqueCode = $this->generateUniqueCode();

        // Buat snapshot data kandidat saat apply
        $candidateSnapshot = [
            'full_name' => $user->full_name ?? $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '-',
            'address' => $user->address ?? '-',
            'birth_date' => $user->birth_date ?? null,
            'gender' => $user->gender ?? '-',
            'education' => $user->education ?? [], // JSON dari users table
            'experience' => $user->experience ?? [], // JSON dari users table
            'profile_photo' => $user->profile_photo ?? null,
            'snapshot_at' => now()->toDateTimeString(), // Timestamp snapshot
        ];

        try {
            // Create application
            $application = Application::create([
                'code' => $uniqueCode, // Required field in database
                'candidate_id' => $user->id,
                'job_posting_id' => $validated['job_posting_id'],
                'application_code' => $applicationCode,
                'candidate_snapshot' => $candidateSnapshot, // Simpan snapshot
                'cv_file' => $cvPath,
                'portfolio_file' => $portfolioPath,
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
                
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Application submission failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'job_posting_id' => $request->job_posting_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengirim lamaran. Silakan coba lagi atau hubungi administrator. Error: ' . $e->getMessage());
        }
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
            'offer.latestNegotiation' // Load latest negotiation for offer
        ])
            ->where('candidate_id', auth()->id())
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }

    /**
     * Generate unique application code
     */
    protected function generateUniqueCode()
    {
        do {
            // Format: APP-YYYY-MM-XXXXX (e.g., APP-2025-11-00001)
            $yearMonth = now()->format('Y-m');
            
            // Get last application for this month
            $lastApplication = Application::where('code', 'like', "APP-{$yearMonth}-%")
                ->orderBy('code', 'desc')
                ->first();
            
            if ($lastApplication) {
                // Extract number and increment
                $lastNumber = (int) substr($lastApplication->code, -5);
                $newNumber = $lastNumber + 1;
            } else {
                // First application this month
                $newNumber = 1;
            }
            
            // Format: APP-YYYY-MM-XXXXX (5 digits, zero-padded)
            $code = sprintf("APP-%s-%05d", $yearMonth, $newNumber);
            
            // Check if code exists (double check for race conditions)
            $exists = Application::where('code', $code)->exists();
            
        } while ($exists);
        
        return $code;
    }
}
