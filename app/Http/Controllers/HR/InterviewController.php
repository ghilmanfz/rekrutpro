<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Interview;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    


    public function index(Request $request)
    {
        $query = Interview::with(['application.candidate', 'application.jobPosting', 'interviewer']);

         
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

         
        if ($request->filled('interviewer_id')) {
            $query->where('interviewer_id', $request->interviewer_id);
        }

         
        if ($request->filled('search')) {
            $query->whereHas('application.candidate', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

         
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $interviews = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        
         
        $interviewers = User::where('email', 'like', '%@%')->take(50)->get();

        return view('hr.interviews.index', compact('interviews', 'interviewers'));
    }

    


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

         
        $application = Application::find($request->application_id);
        $application->update([
            'status' => 'interview_scheduled',
            'interview_scheduled_at' => now(),
        ]);

        AuditLog::log('create', $interview, [], $validated);

         
        $application->load(['candidate', 'jobPosting']);
        $candidate = $application->candidate;
        if ($candidate && $candidate->phone) {
            app(NotificationService::class)->sendWhatsApp(
                'interview_scheduled',
                $candidate->phone,
                [
                    'nama'               => $candidate->full_name ?? $candidate->name,
                    'candidate_name'     => $candidate->full_name ?? $candidate->name,
                    'kode_lamaran'       => $application->application_code ?? $application->code,
                    'application_number' => $application->application_code ?? $application->code,
                    'posisi'             => $application->jobPosting->title ?? '',
                    'job_title'          => $application->jobPosting->title ?? '',
                    'tanggal'            => \Carbon\Carbon::parse($interview->scheduled_at)->format('d/m/Y'),
                    'interview_date'     => \Carbon\Carbon::parse($interview->scheduled_at)->format('d/m/Y'),
                    'waktu'              => \Carbon\Carbon::parse($interview->scheduled_at)->format('H:i'),
                    'interview_time'     => \Carbon\Carbon::parse($interview->scheduled_at)->format('H:i'),
                    'lokasi'             => $interview->location,
                    'interview_location' => $interview->location,
                    'interviewer'        => optional($interview->interviewer)->name ?? '',
                    'interviewer_name'   => optional($interview->interviewer)->name ?? '',
                    'interview_type'     => $interview->interview_type ?? '',
                    'company_name'       => config('app.name', 'RekrutPro'),
                ]
            );
        }

        return redirect()->back()->with('success', 'Jadwal interview berhasil dibuat.');
    }

    


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

         

        return redirect()->back()->with('success', 'Interview berhasil diperbarui.');
    }

    


    public function destroy(Interview $interview)
    {
        if ($interview->status === 'completed') {
            return back()->with('error', 'Tidak dapat menghapus interview yang sudah selesai.');
        }

        $oldData = $interview->toArray();
        
         
        $interview->application->update([
            'status' => 'screening_passed',
        ]);

        $interview->delete();

        AuditLog::log('delete', $interview, $oldData, []);

        return redirect()->route('hr.interviews.index')
            ->with('success', 'Interview berhasil dibatalkan.');
    }
}
