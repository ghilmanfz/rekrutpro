<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    /**
     * Display a listing of interviews
     */
    public function index(Request $request)
    {
        $query = Interview::with(['application.candidate', 'application.jobPosting', 'interviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by interviewer
        if ($request->filled('interviewer_id')) {
            $query->where('interviewer_id', $request->interviewer_id);
        }

        // Search by candidate name
        if ($request->filled('search')) {
            $query->whereHas('application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $interviews = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        
        // Get all active users as potential interviewers (HR staff)
        $interviewers = User::where('email', 'like', '%@%')->take(50)->get();

        return view('hr.interviews.index', compact('interviews', 'interviewers'));
    }

    /**
     * Store a newly created interview
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'interviewer_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'interview_type' => 'required|in:phone,video,onsite',
        ]);

        $validated['status'] = 'scheduled';
        $validated['scheduled_by'] = auth()->id();

        $interview = Interview::create($validated);

        // Update application status
        $application = Application::find($request->application_id);
        $application->update([
            'status' => 'interview_scheduled',
            'interview_scheduled_at' => now(),
        ]);

        AuditLog::log('create', $interview, [], $validated);

        // TODO: Send notification to candidate and interviewer

        return redirect()->back()->with('success', 'Jadwal interview berhasil dibuat.');
    }

    /**
     * Display the specified interview
     */
    public function show(Interview $interview)
    {
        $interview->load([
            'application.candidate',
            'application.jobPosting.position',
            'application.jobPosting.division',
            'interviewer',
            'scheduledBy'
        ]);

        return view('hr.interviews.show', compact('interview'));
    }

    /**
     * Update the specified interview
     */
    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'interviewer_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
            'duration' => 'required|integer|min:15|max:480',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'interview_type' => 'required|in:phone,video,onsite',
            'status' => 'nullable|in:scheduled,completed,cancelled,rescheduled',
        ]);

        $oldData = $interview->toArray();
        $interview->update($validated);

        AuditLog::log('update', $interview, $oldData, $validated);

        // TODO: Send notification if rescheduled

        return redirect()->back()->with('success', 'Interview berhasil diperbarui.');
    }

    /**
     * Remove the specified interview
     */
    public function destroy(Interview $interview)
    {
        if ($interview->status === 'completed') {
            return back()->with('error', 'Tidak dapat menghapus interview yang sudah selesai.');
        }

        $oldData = $interview->toArray();
        
        // Update application status back to screening_passed
        $interview->application->update([
            'status' => 'screening_passed',
        ]);

        $interview->delete();

        AuditLog::log('delete', $interview, $oldData, []);

        return redirect()->route('hr.interviews.index')
            ->with('success', 'Interview berhasil dibatalkan.');
    }
}
