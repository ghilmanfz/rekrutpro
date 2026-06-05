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

        $application->update(array_merge([
            'status' => $newStatus,
            'status_notes' => $request->notes,
        ], $this->statusTimestampUpdates($newStatus)));

         
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
            'screening_passed'      => 'screening_passed',
            'interview_scheduled'  => 'application_interview_scheduled',
            'interview_passed'      => 'interview_passed',
            'offered'               => 'application_offered',
            'hired'                 => 'application_hired',
            'rejected_admin'        => 'screening_rejected',
            'rejected_interview'    => 'interview_rejected',
        ];

        if (isset($eventMap[$newStatus])) {
            $application->load(['candidate', 'jobPosting', 'offer']);
            $candidate = $application->candidate;
            if ($candidate && $candidate->phone) {
                app(NotificationService::class)->sendWhatsApp(
                    $eventMap[$newStatus],
                    $candidate->phone,
                    $this->whatsAppStatusPayload($application, $candidate)
                );
            }
        }

        return redirect()->back()->with('success', 'Status aplikasi berhasil diperbarui');
    }

    private function whatsAppStatusPayload(Application $application, User $candidate): array
    {
        $offer = $application->offer;

        return [
            'nama'               => $candidate->full_name ?? $candidate->name,
            'candidate_name'     => $candidate->full_name ?? $candidate->name,
            'kode_lamaran'       => $application->application_code ?? $application->code,
            'application_number' => $application->application_code ?? $application->code,
            'posisi'             => $application->jobPosting->title ?? '',
            'job_title'          => $application->jobPosting->title ?? '',
            'status'             => $this->statusLabel($application->status),
            'gaji'               => $offer ? 'Rp ' . number_format((float) $offer->salary, 0, ',', '.') : '',
            'salary_range'       => $offer ? 'Rp ' . number_format((float) $offer->salary, 0, ',', '.') : '',
            'start_date'         => $offer?->start_date?->format('d/m/Y') ?? '',
            'company_name'       => config('app.name', 'RekrutPro'),
        ];
    }

    private function statusTimestampUpdates(string $status): array
    {
        $timestampColumns = [
            'screening_passed' => 'screening_passed_at',
            'interview_scheduled' => 'interview_scheduled_at',
            'interview_passed' => 'interview_passed_at',
            'offered' => 'offered_at',
            'hired' => 'hired_at',
        ];

        if (! isset($timestampColumns[$status])) {
            return [];
        }

        $updates = [$timestampColumns[$status] => now()];
        $clearFollowing = false;

        foreach ($timestampColumns as $statusKey => $column) {
            if ($clearFollowing) {
                $updates[$column] = null;
            }

            if ($statusKey === $status) {
                $clearFollowing = true;
            }
        }

        return $updates;
    }

    private function statusLabel(string $status): string
    {
        return [
            'submitted' => 'Baru Diterima',
            'screening_passed' => 'Lolos Screening',
            'interview_scheduled' => 'Terjadwal Interview',
            'interview_passed' => 'Lolos Interview',
            'offered' => 'Ditawarkan',
            'hired' => 'Diterima Kerja',
            'rejected_admin' => 'Ditolak Admin',
            'rejected_interview' => 'Ditolak Interview',
        ][$status] ?? ucfirst(str_replace('_', ' ', $status));
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
