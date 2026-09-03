<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Division;
use App\Models\JobPosting;
use App\Models\Location;
use App\Models\NotificationTemplate;
use App\Models\Position;
use App\Models\Role;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientRevisionValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_candidate_sees_clear_apply_call_to_action(): void
    {
        [$candidate, $job] = $this->candidateAndPublishedJob();

        $response = $this->actingAs($candidate)->get(route('jobs.show', $job));

        $response->assertOk()
            ->assertSee('Lamar Sekarang')
            ->assertDontSee('Silakan login atau daftar untuk melamar posisi ini');

        $this->actingAs($candidate)
            ->get(route('candidate.applications.create', $job))
            ->assertOk()
            ->assertSee('Data ini diambil dari profil Anda');
    }

    public function test_candidate_profile_rejects_letters_in_whatsapp_number(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();
        $originalPhone = $candidate->phone;

        $response = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => '62812ABC456',
            'date_of_birth' => $candidate->date_of_birth->toDateString(),
            'address' => 'Jakarta',
            'education' => 'S1',
            'study_program' => 'Teknik Informatika',
        ]);

        $response->assertRedirect()->assertSessionHasErrors('phone');
        $this->assertSame($originalPhone, $candidate->fresh()->phone);
    }

    public function test_candidate_profile_accepts_legacy_and_new_numeric_formats(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();

        $legacyResponse = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => '081234567890',
            'date_of_birth' => $candidate->date_of_birth->toDateString(),
            'address' => 'Jakarta',
            'education' => 'S1',
            'study_program' => 'Teknik Informatika',
        ]);
        $legacyResponse->assertRedirect()->assertSessionHasNoErrors();

        $newResponse = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => '6281234567890',
            'date_of_birth' => $candidate->date_of_birth->toDateString(),
            'address' => 'Jakarta',
            'education' => 'S1',
            'study_program' => 'Teknik Informatika',
        ]);
        $newResponse->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('6281234567890', $candidate->fresh()->phone);
    }

    public function test_saved_birth_date_and_study_program_are_rendered_again(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();

        $response = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => $candidate->phone,
            'date_of_birth' => '1999-12-31',
            'address' => 'Bandung',
            'education' => 'S1',
            'study_program' => 'Sistem Informasi',
        ]);

        $response->assertRedirect()->assertSessionHasNoErrors();
        $candidate->refresh();
        $this->assertSame('1999-12-31', $candidate->date_of_birth->toDateString());
        $this->assertSame('Sistem Informasi', $candidate->study_program);

        $this->actingAs($candidate)
            ->get(route('candidate.profile'))
            ->assertOk()
            ->assertSee('value="1999-12-31"', false)
            ->assertSee('value="Sistem Informasi"', false);
    }

    public function test_candidate_can_store_a_reusable_cv_on_the_profile(): void
    {
        Storage::fake('public');
        [$candidate] = $this->candidateAndPublishedJob();

        $response = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => $candidate->phone,
            'date_of_birth' => $candidate->date_of_birth->toDateString(),
            'address' => $candidate->address,
            'education' => $candidate->education,
            'study_program' => $candidate->study_program,
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('candidate.profile'))->assertSessionHasNoErrors();
        $candidate->refresh();
        $this->assertNotNull($candidate->cv_path);
        Storage::disk('public')->assertExists($candidate->cv_path);
    }

    public function test_complete_required_profile_is_reported_as_one_hundred_percent(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();

        $this->actingAs($candidate)
            ->get(route('candidate.profile'))
            ->assertOk()
            ->assertSee('100%');
    }

    public function test_incomplete_candidate_returns_to_the_same_application_after_updating_profile(): void
    {
        [$candidate, $job] = $this->candidateAndPublishedJob();
        $candidate->update([
            'date_of_birth' => null,
            'study_program' => null,
        ]);

        $this->actingAs($candidate)
            ->get(route('candidate.applications.create', $job))
            ->assertRedirect(route('candidate.profile'))
            ->assertSessionHas('candidate_apply_after_profile_job_id', $job->id)
            ->assertSessionHas('error');

        $response = $this->actingAs($candidate)->put(route('candidate.profile.update'), [
            'name' => $candidate->name,
            'email' => $candidate->email,
            'phone' => $candidate->phone,
            'date_of_birth' => '2000-06-06',
            'address' => 'Jakarta',
            'education' => 'S1',
            'study_program' => 'Teknik Informatika',
            'return_job_id' => $job->id,
        ]);

        $response
            ->assertRedirect(route('candidate.applications.create', $job))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');
    }

    public function test_registration_step_four_saves_required_profile_without_optional_fields(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();
        $candidate->update([
            'registration_step' => 3,
            'registration_completed' => false,
            'date_of_birth' => null,
            'address' => null,
            'education' => null,
            'study_program' => null,
            'experience' => null,
            'skills' => null,
        ]);

        $response = $this->actingAs($candidate)->post(route('register.step4.process'), [
            'date_of_birth' => '2001-01-15',
            'address' => 'Bandung',
            'education' => 'D3',
            'study_program' => 'Manajemen Informatika',
        ]);

        $response->assertRedirect(route('register.step5'))->assertSessionHasNoErrors();
        $candidate->refresh();
        $this->assertSame('2001-01-15', $candidate->date_of_birth->toDateString());
        $this->assertSame('Manajemen Informatika', $candidate->study_program);
        $this->assertNull($candidate->experience);
        $this->assertNull($candidate->skills);
    }

    public function test_registration_step_four_rejects_future_birth_date_and_missing_study_program(): void
    {
        [$candidate] = $this->candidateAndPublishedJob();
        $candidate->update([
            'registration_step' => 3,
            'registration_completed' => false,
        ]);

        $response = $this->actingAs($candidate)->post(route('register.step4.process'), [
            'date_of_birth' => now()->addDay()->toDateString(),
            'address' => 'Bandung',
            'education' => 'S1',
        ]);

        $response->assertRedirect()->assertSessionHasErrors(['date_of_birth', 'study_program']);
    }

    public function test_application_reuses_profile_education_and_cv_in_snapshot(): void
    {
        Storage::fake('public');
        Http::fake();
        [$candidate, $job] = $this->candidateAndPublishedJob();
        Storage::disk('public')->put('cvs/profile-cv.pdf', 'profile cv');
        $candidate->update(['cv_path' => 'cvs/profile-cv.pdf']);
        $candidate->refresh();
        $this->assertSame('cvs/profile-cv.pdf', $candidate->cv_path);
        $this->assertTrue(Storage::disk('public')->exists($candidate->cv_path));
        NotificationTemplate::create([
            'name' => 'Application Submitted Test',
            'slug' => 'application-submitted-test',
            'type' => 'whatsapp',
            'channel' => 'whatsapp',
            'event' => 'application_submitted',
            'body' => 'Lamaran {{nama}} untuk {{posisi}} diterima.',
            'is_active' => true,
        ]);
        SystemConfig::set('whatsapp_api_key', 'test-api-key');

        $this->actingAs($candidate)
            ->get(route('candidate.applications.create', $job))
            ->assertOk()
            ->assertSee('CV profil akan digunakan otomatis')
            ->assertSee('Teknik Informatika')
            ->assertDontSee('name="education"', false);

        $response = $this->actingAs($candidate)->post(route('candidate.applications.store'), [
            'job_posting_id' => $job->id,
            'experience' => '1-3 Tahun',
            'expected_salary' => 10000000,
            'availability' => '1 Bulan',
            'agree_terms' => '1',
            'cover_letter' => 'Saya tertarik dengan posisi ini.',
        ]);

        $application = Application::where('candidate_id', $candidate->id)->firstOrFail();
        $response->assertRedirect(route('candidate.applications.show', $application));
        $this->assertSame('cvs/profile-cv.pdf', $application->cv_file);
        $this->assertSame('S1', $application->education_level);
        $this->assertSame('Teknik Informatika', $application->study_program);
        $this->assertSame('1-3 Tahun', $application->experience_level);
        $this->assertSame('2000-06-06', $application->candidate_birth_date);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.fonnte.com/send'
            && $request['target'] === $candidate->phone);
    }

    public function test_legacy_double_encoded_snapshot_keeps_education_and_change_detection(): void
    {
        [$candidate, $job] = $this->candidateAndPublishedJob();

        $application = Application::create([
            'code' => 'APP-LEGACY-001',
            'application_code' => 'LEGACY-001',
            'candidate_id' => $candidate->id,
            'job_posting_id' => $job->id,
            'candidate_snapshot' => json_encode([
                'full_name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'address' => $candidate->address,
                'date_of_birth' => $candidate->date_of_birth->toDateString(),
                'education' => [[
                    'degree' => 'S1',
                    'major' => 'Teknik Informatika',
                ]],
                'experience' => [],
                'skills' => null,
            ]),
            'status' => 'submitted',
        ]);

        $application->refresh();
        $this->assertSame('S1', $application->education_level);
        $this->assertSame('Teknik Informatika', $application->study_program);
        $this->assertSame('2000-06-06', $application->candidate_birth_date);
        $this->assertFalse($application->hasProfileChangedSinceApply());

        $candidate->update(['study_program' => 'Sistem Informasi']);
        $application->unsetRelation('candidate');
        $this->assertTrue($application->hasProfileChangedSinceApply());
    }

    public function test_application_specific_fields_reject_invalid_values(): void
    {
        [$candidate, $job] = $this->candidateAndPublishedJob();

        $response = $this->actingAs($candidate)->post(route('candidate.applications.store'), [
            'job_posting_id' => $job->id,
            'experience' => 'Nilai Buatan',
            'expected_salary' => 'sepuluh juta',
            'availability' => 'Kapan Saja',
            'agree_terms' => '1',
            'cover_letter' => str_repeat('a', 256),
        ]);

        $response->assertRedirect()->assertSessionHasErrors([
            'experience',
            'expected_salary',
            'availability',
            'cover_letter',
        ]);
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_fonnte_configuration_requires_628_numeric_format(): void
    {
        $superAdminRole = Role::create([
            'name' => Role::SUPER_ADMIN,
            'display_name' => 'Super Admin',
        ]);
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin-test@example.com',
            'password' => 'password',
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);
        SystemConfig::set('whatsapp_phone', '628111111111');

        $response = $this->actingAs($superAdmin)->post(route('superadmin.config.whatsapp'), [
            'whatsapp_phone' => '62812ABC456',
            'whatsapp_api_key' => 'test-key',
        ]);

        $response->assertRedirect()->assertSessionHasErrors('whatsapp_phone');
        $this->assertSame('628111111111', SystemConfig::get('whatsapp_phone'));
    }

    public function test_existing_fonnte_token_is_hidden_and_preserved_when_left_blank(): void
    {
        $superAdminRole = Role::create([
            'name' => Role::SUPER_ADMIN,
            'display_name' => 'Super Admin',
        ]);
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin-secret-test@example.com',
            'password' => 'password',
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);
        SystemConfig::set('whatsapp_phone', '628111111111');
        SystemConfig::set('whatsapp_api_key', 'existing-secret-token');

        $this->actingAs($superAdmin)
            ->get(route('superadmin.config.index'))
            ->assertOk()
            ->assertDontSee('existing-secret-token')
            ->assertSee('Token saat ini tersimpan dan tidak ditampilkan kembali.');

        $response = $this->actingAs($superAdmin)->post(route('superadmin.config.whatsapp'), [
            'whatsapp_phone' => '628222222222',
            'whatsapp_api_key' => '',
        ]);

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('628222222222', SystemConfig::get('whatsapp_phone'));
        $this->assertSame('existing-secret-token', SystemConfig::get('whatsapp_api_key'));
    }

    private function candidateAndPublishedJob(): array
    {
        $candidateRole = Role::firstOrCreate(
            ['name' => Role::CANDIDATE],
            ['display_name' => 'Kandidat']
        );
        $hrRole = Role::firstOrCreate(
            ['name' => Role::HR],
            ['display_name' => 'HR']
        );

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
        $hr = User::create([
            'name' => 'HR User',
            'email' => 'hr-ui-test@example.com',
            'password' => 'password',
            'role_id' => $hrRole->id,
            'is_active' => true,
        ]);
        $candidate = User::create([
            'name' => 'Candidate User',
            'email' => 'candidate-ui-test@example.com',
            'phone' => '081234567890',
            'date_of_birth' => '2000-06-06',
            'address' => 'Jakarta',
            'education' => 'S1',
            'study_program' => 'Teknik Informatika',
            'password' => 'password',
            'role_id' => $candidateRole->id,
            'is_active' => true,
            'is_verified' => true,
            'registration_step' => 5,
            'registration_completed' => true,
        ]);
        $job = JobPosting::create([
            'code' => 'ENG-001',
            'position_id' => $position->id,
            'division_id' => $division->id,
            'location_id' => $location->id,
            'created_by' => $hr->id,
            'title' => 'Software Engineer',
            'description' => 'Membangun aplikasi.',
            'status' => 'active',
            'published_at' => now()->subDay(),
            'closed_at' => now()->addMonth(),
        ]);

        return [$candidate, $job];
    }
}
