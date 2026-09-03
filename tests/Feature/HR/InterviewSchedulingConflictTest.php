<?php

namespace Tests\Feature\HR;

use App\Models\Application;
use App\Models\Division;
use App\Models\Interview;
use App\Models\JobPosting;
use App\Models\Location;
use App\Models\NotificationTemplate;
use App\Models\Position;
use App\Models\Role;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InterviewSchedulingConflictTest extends TestCase
{
    use RefreshDatabase;

    private User $hr;

    private User $interviewer;

    private Application $firstApplication;

    private Application $secondApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $hrRole = Role::create([
            'name' => Role::HR,
            'display_name' => 'HR',
        ]);
        $interviewerRole = Role::create([
            'name' => Role::INTERVIEWER,
            'display_name' => 'Interviewer',
        ]);
        $candidateRole = Role::create([
            'name' => Role::CANDIDATE,
            'display_name' => 'Kandidat',
        ]);

        $division = Division::create([
            'name' => 'Teknologi',
            'code' => 'TECH',
        ]);
        $position = Position::create([
            'division_id' => $division->id,
            'name' => 'Engineer',
            'code' => 'ENG',
        ]);
        $location = Location::create([
            'name' => 'Jakarta',
            'code' => 'JKT',
        ]);

        $this->hr = User::create([
            'name' => 'HR User',
            'email' => 'hr-test@example.com',
            'password' => 'password',
            'role_id' => $hrRole->id,
            'is_active' => true,
        ]);
        $this->interviewer = User::create([
            'name' => 'Interviewer User',
            'email' => 'interviewer-test@example.com',
            'password' => 'password',
            'role_id' => $interviewerRole->id,
            'is_active' => true,
        ]);

        $firstCandidate = $this->candidate($candidateRole, 'candidate-one@example.com', '628111111111');
        $secondCandidate = $this->candidate($candidateRole, 'candidate-two@example.com', '628222222222');

        $job = JobPosting::create([
            'code' => 'ENG-001',
            'position_id' => $position->id,
            'division_id' => $division->id,
            'location_id' => $location->id,
            'created_by' => $this->hr->id,
            'title' => 'Software Engineer',
            'description' => 'Membangun aplikasi.',
            'status' => 'active',
            'published_at' => now()->subDay(),
            'closed_at' => now()->addMonth(),
        ]);

        $this->firstApplication = $this->application($job, $firstCandidate, 'APP-TEST-001');
        $this->secondApplication = $this->application($job, $secondCandidate, 'APP-TEST-002');

        NotificationTemplate::create([
            'name' => 'Interview Scheduled Test',
            'slug' => 'interview-scheduled-test',
            'type' => 'whatsapp',
            'channel' => 'whatsapp',
            'event' => 'interview_scheduled',
            'body' => 'Interview {{nama}} pada {{tanggal}} {{waktu}}.',
            'is_active' => true,
        ]);
        SystemConfig::set('whatsapp_api_key', 'test-api-key');
    }

    public function test_overlapping_schedule_for_same_interviewer_is_rejected(): void
    {
        Http::fake();
        $startsAt = now()->addDays(2)->startOfHour();
        $this->existingInterview($startsAt, 60);

        $response = $this->actingAs($this->hr)->post(route('hr.interviews.store'),
            $this->schedulePayload($this->secondApplication, $startsAt->copy()->addMinutes(30), 60)
        );

        $response->assertRedirect()->assertSessionHasErrors(['scheduled_at', 'schedule_conflict']);
        $this->assertDatabaseCount('interviews', 1);
        $this->assertDatabaseHas('applications', [
            'id' => $this->secondApplication->id,
            'status' => 'screening_passed',
        ]);
        $this->assertDatabaseCount('audit_logs', 0);
        Http::assertNothingSent();

        $this->actingAs($this->hr)
            ->get(route('hr.applications.show', $this->secondApplication))
            ->assertOk()
            ->assertSee('Jadwal Interview Bentrok')
            ->assertSee('Perbaiki Jadwal')
            ->assertSee('role="alertdialog"', false);
    }

    public function test_same_start_time_for_same_interviewer_is_rejected(): void
    {
        Http::fake();
        $startsAt = now()->addDays(2)->startOfHour();
        $this->existingInterview($startsAt, 60);

        $response = $this->actingAs($this->hr)->post(
            route('hr.interviews.store'),
            $this->schedulePayload($this->secondApplication, $startsAt, 30)
        );

        $response->assertRedirect()->assertSessionHasErrors('scheduled_at');
        $this->assertDatabaseCount('interviews', 1);
        Http::assertNothingSent();
    }

    public function test_back_to_back_schedule_is_allowed_and_existing_flow_still_runs(): void
    {
        Http::fake();
        $startsAt = now()->addDays(2)->startOfHour();
        $this->existingInterview($startsAt, 60);

        $response = $this->actingAs($this->hr)->post(route('hr.interviews.store'),
            $this->schedulePayload($this->secondApplication, $startsAt->copy()->addHour(), 60)
        );

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('interviews', 2);
        $this->assertDatabaseHas('applications', [
            'id' => $this->secondApplication->id,
            'status' => 'interview_scheduled',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'create',
            'model_type' => Interview::class,
        ]);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.fonnte.com/send'
            && $request['target'] === '628222222222'
            && $request['connectOnly'] === false);
    }

    public function test_schedule_is_saved_but_hr_sees_warning_when_fonnte_rejects_notification(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response([
                'status' => false,
                'reason' => 'device disconnected',
                'requestid' => 12345,
            ], 200),
        ]);

        $startsAt = now()->addDays(2)->startOfHour();

        $response = $this->actingAs($this->hr)->post(
            route('hr.interviews.store'),
            $this->schedulePayload($this->secondApplication, $startsAt, 60)
        );

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success')
            ->assertSessionHas('warning');

        $this->assertDatabaseHas('applications', [
            'id' => $this->secondApplication->id,
            'status' => 'interview_scheduled',
        ]);
        $this->assertDatabaseCount('interviews', 1);
    }

    public function test_inactive_rescheduled_row_does_not_block_the_current_slot(): void
    {
        Http::fake();
        $startsAt = now()->addDays(2)->startOfHour();
        $this->existingInterview($startsAt, 60, 'rescheduled');

        $response = $this->actingAs($this->hr)->post(
            route('hr.interviews.store'),
            $this->schedulePayload($this->secondApplication, $startsAt, 60)
        );

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('interviews', 2);
    }

    public function test_different_interviewer_can_use_the_same_time_slot(): void
    {
        Http::fake();
        $startsAt = now()->addDays(2)->startOfHour();
        $this->existingInterview($startsAt, 60);

        $otherInterviewer = User::create([
            'name' => 'Other Interviewer',
            'email' => 'other-interviewer@example.com',
            'password' => 'password',
            'role_id' => Role::where('name', Role::INTERVIEWER)->value('id'),
            'is_active' => true,
        ]);
        $payload = $this->schedulePayload($this->secondApplication, $startsAt, 60);
        $payload['interviewer_id'] = $otherInterviewer->id;

        $response = $this->actingAs($this->hr)->post(route('hr.interviews.store'), $payload);

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('interviews', [
            'application_id' => $this->secondApplication->id,
            'interviewer_id' => $otherInterviewer->id,
        ]);
    }

    public function test_update_excludes_itself_but_rejects_another_interview_overlap(): void
    {
        $startsAt = now()->addDays(2)->startOfHour();
        $firstInterview = $this->existingInterview($startsAt, 60);
        $secondInterview = Interview::create([
            'application_id' => $this->secondApplication->id,
            'interviewer_id' => $this->interviewer->id,
            'scheduled_by' => $this->hr->id,
            'scheduled_at' => $startsAt->copy()->addHours(2),
            'duration' => 60,
            'interview_type' => 'video',
            'location' => 'Meeting Room 2',
            'status' => 'scheduled',
        ]);

        $selfResponse = $this->actingAs($this->hr)->put(route('hr.interviews.update', $firstInterview), [
            'interviewer_id' => $this->interviewer->id,
            'scheduled_at' => $startsAt->format('Y-m-d H:i:s'),
            'duration' => 60,
            'interview_type' => 'video',
            'location' => 'Meeting Room 1',
            'status' => 'scheduled',
        ]);
        $selfResponse->assertRedirect()->assertSessionHasNoErrors();

        $conflictResponse = $this->actingAs($this->hr)->put(route('hr.interviews.update', $secondInterview), [
            'interviewer_id' => $this->interviewer->id,
            'scheduled_at' => $startsAt->copy()->addMinutes(15)->format('Y-m-d H:i:s'),
            'duration' => 60,
            'interview_type' => 'video',
            'location' => 'Meeting Room 2',
            'status' => 'scheduled',
        ]);

        $conflictResponse->assertRedirect()->assertSessionHasErrors('scheduled_at');
        $this->assertDatabaseHas('interviews', [
            'id' => $secondInterview->id,
            'scheduled_at' => $startsAt->copy()->addHours(2)->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_hr_status_change_and_fonnte_notification_flow_remain_available(): void
    {
        Http::fake();

        $response = $this->actingAs($this->hr)->put(
            route('hr.applications.update-status', $this->secondApplication),
            [
                'status' => 'hired',
                'notes' => 'Kandidat diterima.',
            ]
        );

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('applications', [
            'id' => $this->secondApplication->id,
            'status' => 'hired',
            'status_notes' => 'Kandidat diterima.',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'model_type' => Application::class,
            'model_id' => $this->secondApplication->id,
            'action' => 'status_update',
        ]);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.fonnte.com/send'
            && $request['target'] === '628222222222'
            && str_contains($request['message'], 'diterima kerja'));
    }

    private function candidate(Role $role, string $email, string $phone): User
    {
        return User::create([
            'name' => $email,
            'email' => $email,
            'phone' => $phone,
            'address' => 'Jakarta',
            'password' => 'password',
            'role_id' => $role->id,
            'is_active' => true,
            'is_verified' => true,
            'registration_step' => 5,
            'registration_completed' => true,
        ]);
    }

    private function application(JobPosting $job, User $candidate, string $code): Application
    {
        return Application::create([
            'code' => $code,
            'application_code' => $code,
            'job_posting_id' => $job->id,
            'candidate_id' => $candidate->id,
            'candidate_snapshot' => [
                'full_name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'address' => $candidate->address,
            ],
            'status' => 'screening_passed',
        ]);
    }

    private function existingInterview($startsAt, int $duration, string $status = 'scheduled'): Interview
    {
        return Interview::create([
            'application_id' => $this->firstApplication->id,
            'interviewer_id' => $this->interviewer->id,
            'scheduled_by' => $this->hr->id,
            'scheduled_at' => $startsAt,
            'duration' => $duration,
            'interview_type' => 'video',
            'location' => 'Meeting Room 1',
            'status' => $status,
        ]);
    }

    private function schedulePayload(Application $application, $startsAt, int $duration): array
    {
        return [
            'application_id' => $application->id,
            'interviewer_id' => $this->interviewer->id,
            'scheduled_at' => $startsAt->format('Y-m-d H:i:s'),
            'duration' => $duration,
            'interview_type' => 'video',
            'location' => 'Meeting Room',
            'notes' => 'Test schedule',
        ];
    }
}
