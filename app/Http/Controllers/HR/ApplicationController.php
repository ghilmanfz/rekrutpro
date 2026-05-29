<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\JobPosting;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    


    public function index(Request $request)
    {
        $query = Application::with(['candidate', 'jobPosting.division', 'jobPosting.location']);

         
        if ($request->filled('search')) {
            $query->whereHas('candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

         
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

         
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(15);
        $jobs = JobPosting::where('status', 'active')->get();

        return view('hr.applications.index', compact('applications', 'jobs'));
    }

    


    public function show(Application $application)
    {
        $application->load([
            'candidate', 
            'jobPosting.position', 
            'jobPosting.division', 
            'jobPosting.location',
            'offer',
            'interviews.interviewer',
            'interviews.assessment',
        ]);

        $interviewers = User::withRoleName(Role::INTERVIEWER)
            ->orderBy('name')
            ->get();

        return view('hr.applications.show', compact('application', 'interviewers'));
    }

    


    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:screening_passed,interview_scheduled,interview_passed,offered,hired,rejected_admin,rejected_interview',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

         
        $application->update([
            'status' => $newStatus,
            'status_notes' => $request->notes,
        ]);

         
        switch ($newStatus) {
            case 'screening_passed':
                $application->update(['screening_passed_at' => now()]);
                break;
            case 'interview_scheduled':
                $application->update(['interview_scheduled_at' => now()]);
                break;
            case 'interview_passed':
                $application->update(['interview_passed_at' => now()]);
                break;
            case 'offered':
                $application->update(['offered_at' => now()]);
                break;
            case 'hired':
                $application->update(['hired_at' => now()]);
                break;
        }

         
        AuditLog::create([
            'user_id' => auth()->id(),
            'model_type' => Application::class,
            'model_id' => $application->id,
            'action' => 'status_update',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus, 'notes' => $request->notes],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

         
        $eventMap = [
            'screening_passed'   => 'screening_passed',
            'interview_passed'   => 'interview_passed',
            'rejected_admin'     => 'screening_rejected',
            'rejected_interview' => 'interview_rejected',
        ];

        if (isset($eventMap[$newStatus])) {
            $application->load(['candidate', 'jobPosting']);
            $candidate = $application->candidate;
            if ($candidate && $candidate->phone) {
                app(NotificationService::class)->sendWhatsApp(
                    $eventMap[$newStatus],
                    $candidate->phone,
                    [
                        'nama'           => $candidate->full_name ?? $candidate->name,
                        'candidate_name' => $candidate->full_name ?? $candidate->name,
                        'kode_lamaran'   => $application->application_code ?? $application->code,
                        'application_number' => $application->application_code ?? $application->code,
                        'posisi'         => $application->jobPosting->title ?? '',
                        'job_title'      => $application->jobPosting->title ?? '',
                        'company_name'   => config('app.name', 'RekrutPro'),
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Status aplikasi berhasil diperbarui');
    }

    


    public function export(Request $request)
    {
        $applications = Application::with(['candidate', 'jobPosting.division', 'jobPosting.location'])
            ->latest()
            ->get();

        $filename = 'applications-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Kode Lamaran',
                'Nama Kandidat',
                'Email',
                'Posisi',
                'Divisi',
                'Lokasi',
                'Status',
                'Tanggal Apply',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, [
                    $application->application_code ?? $application->code,
                    $application->candidate_name,
                    $application->candidate_email,
                    $application->jobPosting?->title,
                    $application->jobPosting?->division?->name,
                    $application->jobPosting?->location?->name,
                    $application->status,
                    $application->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
