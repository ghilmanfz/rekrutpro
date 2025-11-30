<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::with(['candidate', 'jobPosting.division', 'jobPosting.location']);

        // Search by candidate name
        if ($request->filled('search')) {
            $query->whereHas('candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by job
        if ($request->filled('job_id')) {
            $query->where('job_posting_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(15);
        $jobs = JobPosting::where('status', 'active')->get();

        return view('hr.applications.index', compact('applications', 'jobs'));
    }

    /**
     * Display the specified application
     */
    public function show(Application $application)
    {
        $application->load([
            'candidate', 
            'jobPosting.position', 
            'jobPosting.division', 
            'jobPosting.location',
            'offer' // Load offer relationship
        ]);

        return view('hr.applications.show', compact('application'));
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:screening_passed,interview_scheduled,interview_passed,offered,hired,rejected_admin,rejected_interview',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

        // Update status
        $application->update([
            'status' => $newStatus,
            'status_notes' => $request->notes,
        ]);

        // Set timestamp based on status
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

        // Log the status change
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

        // TODO: Send notification to candidate

        return redirect()->back()->with('success', 'Status aplikasi berhasil diperbarui');
    }

    /**
     * Export applications to Excel
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality
        return back()->with('info', 'Export functionality coming soon');
    }
}
