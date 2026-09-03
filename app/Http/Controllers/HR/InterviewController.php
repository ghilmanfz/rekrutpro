<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Interview;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InterviewController extends Controller
{
    private const SCHEDULE_CONFLICT_MESSAGE = 'Interviewer sudah memiliki jadwal dengan kandidat lain pada rentang waktu tersebut.';

    


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
        $interviewerRoleId = Role::where('name', Role::INTERVIEWER)->value('id');

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'interviewer_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role_id', $interviewerRoleId)),
            ],
            'scheduled_at' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:'.Interview::MAX_DURATION_MINUTES,
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'interview_type' => 'required|in:phone,video,onsite',
        ], [
            'interviewer_id.exists' => 'Interviewer yang dipilih tidak valid.',
        ]);

        $validated['status'] = 'scheduled';
        $validated['scheduled_by'] = auth()->id();

        [$interview, $application] = DB::transaction(function () use ($validated, $interviewerRoleId) {
            $lockedInterviewer = User::whereKey($validated['interviewer_id'])
                ->lockForUpdate()
                ->first();

            if (! $interviewerRoleId
                || ! $lockedInterviewer
                || (int) $lockedInterviewer->role_id !== (int) $interviewerRoleId) {
                throw ValidationException::withMessages([
                    'interviewer_id' => 'Interviewer yang dipilih tidak valid.',
                ]);
            }

            $startsAt = Carbon::parse($validated['scheduled_at']);

            if (Interview::hasScheduleConflict(
                (int) $validated['interviewer_id'],
                $startsAt,
                (int) $validated['duration']
            )) {
                throw ValidationException::withMessages([
                    'scheduled_at' => self::SCHEDULE_CONFLICT_MESSAGE,
                    'schedule_conflict' => self::SCHEDULE_CONFLICT_MESSAGE,
                ]);
            }

            $interview = Interview::create($validated);
            $application = Application::whereKey($validated['application_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $application->update([
                'status' => 'interview_scheduled',
                'interview_scheduled_at' => now(),
            ]);

            AuditLog::log('create', $interview, [], $validated);

            return [$interview, $application];
        });

         
        $application->load(['candidate', 'jobPosting']);
        $candidate = $application->candidate;
        $notificationFailed = false;
        if ($candidate && $candidate->phone) {
            $notificationFailed = ! app(NotificationService::class)->sendWhatsApp(
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

        $redirect = redirect()->back()->with('success', 'Jadwal interview berhasil dibuat.');

        if ($notificationFailed) {
            $redirect->with('warning', 'Jadwal tersimpan, tetapi notifikasi WhatsApp gagal dikirim. Periksa koneksi perangkat Fonnte.');
        }

        return $redirect;
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
        $interviewerRoleId = Role::where('name', Role::INTERVIEWER)->value('id');

        $validated = $request->validate([
            'interviewer_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'scheduled_at' => 'required|date',
            'duration' => 'required|integer|min:15|max:'.Interview::MAX_DURATION_MINUTES,
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'interview_type' => 'required|in:phone,video,onsite',
            'status' => 'nullable|in:scheduled,completed,cancelled,rescheduled',
        ], [
            'interviewer_id.exists' => 'Interviewer yang dipilih tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $interview, $interviewerRoleId) {
            $lockedInterview = Interview::whereKey($interview->id)
                ->lockForUpdate()
                ->firstOrFail();

            $interviewerIds = collect([
                (int) $lockedInterview->interviewer_id,
                (int) $validated['interviewer_id'],
            ])->unique()->sort()->values();

            $lockedUsers = User::whereIn('id', $interviewerIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $targetInterviewer = $lockedUsers->get((int) $validated['interviewer_id']);
            $isChangingInterviewer = (int) $validated['interviewer_id'] !== (int) $lockedInterview->interviewer_id;
            $targetHasInterviewerRole = $interviewerRoleId
                && (int) $targetInterviewer?->role_id === (int) $interviewerRoleId;

            if (! $targetInterviewer || ($isChangingInterviewer && ! $targetHasInterviewerRole)) {
                throw ValidationException::withMessages([
                    'interviewer_id' => 'Interviewer yang dipilih tidak valid.',
                ]);
            }

            $effectiveStatus = $validated['status'] ?? $lockedInterview->status;

            if (in_array($effectiveStatus, Interview::SCHEDULE_BLOCKING_STATUSES, true)) {
                $startsAt = Carbon::parse($validated['scheduled_at']);

                if (Interview::hasScheduleConflict(
                    (int) $validated['interviewer_id'],
                    $startsAt,
                    (int) $validated['duration'],
                    $lockedInterview->id
                )) {
                    throw ValidationException::withMessages([
                        'scheduled_at' => self::SCHEDULE_CONFLICT_MESSAGE,
                        'schedule_conflict' => self::SCHEDULE_CONFLICT_MESSAGE,
                    ]);
                }
            }

            $oldData = $lockedInterview->toArray();
            $lockedInterview->update($validated);

            AuditLog::log('update', $lockedInterview, $oldData, $validated);
        });

         

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
