<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\AuditLog;
use App\Services\FileUploadService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    protected $fileUploadService;
    protected $notificationService;

    public function __construct(FileUploadService $fileUploadService, NotificationService $notificationService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->notificationService = $notificationService;
    }

    


    public function index()
    {
        $applications = Application::with(['jobPosting.division', 'jobPosting.position'])
            ->where('candidate_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('candidate.applications.index', compact('applications'));
    }

    


    public function create($jobId)
    {
        $job = JobPosting::with(['division', 'position', 'location'])
            ->where('status', 'active')
            ->findOrFail($jobId);

         
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

    


    public function store(Request $request)
    {
         
         
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'portfolio' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'education' => 'required|string|max:50',
            'experience' => 'required|string|max:50',
            'expected_salary' => 'required|numeric|min:0',
            'availability' => 'required|string|max:50',
            'agree_terms' => 'required|accepted',
        ]);

         
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
             
            $cvPath = $this->fileUploadService->uploadCV(
                $request->file('cv'),
                $user->full_name ?? $user->name
            );

             
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

         
        $applicationCode = 'APP-' . strtoupper(Str::random(8));
        
         
        $uniqueCode = $this->generateUniqueCode();

         
        $candidateSnapshot = [
            'full_name' => $user->full_name ?? $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '-',
            'address' => $user->address ?? '-',
            'birth_date' => $user->date_of_birth?->toDateString(),
            'gender' => $user->gender ?? '-',
            'education' => $user->education ?? [],
            'experience' => $user->experience ?? [],
            'profile_photo' => $user->profile_photo ?? null,

            'education_level' => $validated['education'],
            'experience_level' => $validated['experience'],
            'expected_salary' => $validated['expected_salary'],
            'availability' => $validated['availability'],
            'snapshot_at' => now()->toDateTimeString(),
        ];

        try {
             
            $application = Application::create([
                'code' => $uniqueCode,  
                'candidate_id' => $user->id,
                'job_posting_id' => $validated['job_posting_id'],
                'application_code' => $applicationCode,
                'candidate_snapshot' => $candidateSnapshot,  
                'cv_file' => $cvPath,
                'portfolio_file' => $portfolioPath,
                'cover_letter' => $validated['cover_letter'],
                'status' => 'submitted',
            ]);

             
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'application_submitted',
                'model_type' => 'Application',
                'model_id' => $application->id,
                'description' => 'Kandidat mengirim lamaran untuk posisi: ' . $application->jobPosting->title,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

             
            if ($user->phone) {
                $this->notificationService->sendWhatsApp('application_submitted', $user->phone, [
                    'candidate_name'     => $user->full_name ?? $user->name,
                    'nama'               => $user->full_name ?? $user->name,
                    'job_title'          => $application->jobPosting->title,
                    'posisi'             => $application->jobPosting->title,
                    'application_number' => $applicationCode,
                    'kode_lamaran'       => $applicationCode,
                    'company_name'       => config('app.name', 'RekrutPro'),
                ]);
            }

            return redirect()
                ->route('candidate.applications.show', $application->id)
                ->with('success', 'Lamaran Anda berhasil dikirim! Kode lamaran: ' . $applicationCode);
                
        } catch (\Exception $e) {
             
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

    


    public function show($id)
    {
        $application = Application::with([
            'candidate',
            'jobPosting.division',
            'jobPosting.location',
            'interviews',
            'offer.latestNegotiation'  
        ])
            ->where('candidate_id', auth()->id())
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }

    


    protected function generateUniqueCode()
    {
        do {
             
            $yearMonth = now()->format('Y-m');
            
             
            $lastApplication = Application::where('code', 'like', "APP-{$yearMonth}-%")
                ->orderBy('code', 'desc')
                ->first();
            
            if ($lastApplication) {
                 
                $lastNumber = (int) substr($lastApplication->code, -5);
                $newNumber = $lastNumber + 1;
            } else {
                 
                $newNumber = 1;
            }
            
             
            $code = sprintf("APP-%s-%05d", $yearMonth, $newNumber);
            
             
            $exists = Application::where('code', $code)->exists();
            
        } while ($exists);
        
        return $code;
    }
}
