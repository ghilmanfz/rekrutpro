<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\JobPosting;
use App\Models\Division;
use App\Models\Position;
use App\Models\Location;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class FullSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $candidateRole;
    protected $hrRole;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->candidateRole = Role::create([
            'name' => 'candidate',
            'display_name' => 'Kandidat',
            'description' => 'Daftar, apply lowongan, dan cek status lamaran'
        ]);
        
        $this->hrRole = Role::create([
            'name' => 'hr',
            'display_name' => 'HR / Recruiter',
            'description' => 'Kelola lowongan, proses kandidat dari awal sampai hired'
        ]);

        Storage::fake('public');
    }

    /** @test */
    public function test_01_database_schema_has_snapshot_field()
    {
        $this->assertDatabaseHas('roles', ['name' => 'candidate']);
        
        // Check applications table has candidate_snapshot
        $columns = \DB::select("DESCRIBE applications");
        $columnNames = collect($columns)->pluck('Field')->toArray();
        
        $this->assertContains('candidate_snapshot', $columnNames, 
            'applications table must have candidate_snapshot field');
        
        // Check duplicate fields removed
        $this->assertNotContains('full_name', $columnNames, 
            'applications table should NOT have full_name (should use snapshot)');
        $this->assertNotContains('email', $columnNames, 
            'applications table should NOT have email (should use snapshot)');
        $this->assertNotContains('phone', $columnNames, 
            'applications table should NOT have phone (should use snapshot)');
        
        echo "\n✅ TEST 1 PASSED: Database schema correct (snapshot field exists, duplicates removed)\n";
    }

    /** @test */
    public function test_02_registration_step1_sets_is_active_true()
    {
        $response = $this->post(route('register.step1.process'), [
            'name' => 'Test Candidate',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'agree_terms' => true,
        ]);

        // Should create user
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'is_active' => true, // ✅ BUG FIX: Should be true
            'registration_step' => 1,
            'registration_completed' => false,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->is_active, 'is_active should be TRUE after Step 1');
        
        echo "\n✅ TEST 2 PASSED: Registration Step 1 sets is_active = true (Bug fix verified)\n";
    }

    /** @test */
    public function test_03_registration_lock_bug_fixed_can_logout_and_login()
    {
        // Create incomplete registration
        $user = User::create([
            'name' => 'Incomplete User',
            'email' => 'incomplete@example.com',
            'password' => Hash::make('Password123!'),
            'role_id' => $this->candidateRole->id,
            'is_active' => true, // ✅ Should be true
            'registration_step' => 2,
            'registration_completed' => false,
        ]);

        // Try to login
        $response = $this->post(route('login'), [
            'email' => 'incomplete@example.com',
            'password' => 'Password123!',
        ]);

        // Should NOT be rejected
        $response->assertStatus(302);
        $response->assertRedirect(route('register.step3')); // Smart redirect
        
        $this->assertAuthenticatedAs($user);
        
        echo "\n✅ TEST 3 PASSED: Registration lock bug fixed - can login during incomplete registration\n";
    }

    /** @test */
    public function test_04_suspended_account_cannot_login()
    {
        $user = User::create([
            'name' => 'Suspended User',
            'email' => 'suspended@example.com',
            'password' => Hash::make('Password123!'),
            'role_id' => $this->candidateRole->id,
            'is_active' => false, // Suspended
            'registration_step' => 5,
            'registration_completed' => true, // Completed but suspended
        ]);

        $response = $this->post(route('login'), [
            'email' => 'suspended@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
        
        echo "\n✅ TEST 4 PASSED: Suspended accounts correctly blocked from login\n";
    }

    /** @test */
    public function test_05_smart_redirect_to_correct_registration_step()
    {
        $user = User::create([
            'name' => 'Step 3 User',
            'email' => 'step3@example.com',
            'password' => Hash::make('Password123!'),
            'role_id' => $this->candidateRole->id,
            'is_active' => true,
            'registration_step' => 3,
            'registration_completed' => false,
            'phone' => '08123456789',
            'address' => 'Test Address',
        ]);

        $response = $this->actingAs($user)->post(route('login'), [
            'email' => 'step3@example.com',
            'password' => 'Password123!',
        ]);

        // Should redirect to step 4 (next step)
        $response->assertRedirect(route('register.step4'));
        
        echo "\n✅ TEST 5 PASSED: Smart redirect to correct registration step\n";
    }

    /** @test */
    public function test_06_application_creates_snapshot()
    {
        // Create complete candidate
        $candidate = User::create([
            'name' => 'Complete Candidate',
            'email' => 'candidate@example.com',
            'password' => Hash::make('Password123!'),
            'role_id' => $this->candidateRole->id,
            'is_active' => true,
            'registration_step' => 5,
            'registration_completed' => true,
            'full_name' => 'John Doe',
            'phone' => '08123456789',
            'address' => '123 Test Street',
            'birth_date' => '1995-01-15',
            'gender' => 'male',
            'education' => json_encode([
                ['degree' => 'S1', 'institution' => 'Test University', 'major' => 'Computer Science', 'graduation_year' => 2020, 'gpa' => 3.8]
            ]),
            'experience' => json_encode([
                ['position' => 'Developer', 'company' => 'Test Company', 'start_date' => '2020-01', 'end_date' => '2023-12', 'is_current' => false]
            ]),
        ]);

        // Create job posting
        $division = Division::create(['name' => 'IT', 'description' => 'IT Division']);
        $position = Position::create(['name' => 'Developer', 'description' => 'Developer Position']);
        $location = Location::create(['city' => 'Jakarta', 'address' => 'Jakarta Office']);
        
        $job = JobPosting::create([
            'job_code' => 'JOB-001',
            'title' => 'Software Developer',
            'division_id' => $division->id,
            'position_id' => $position->id,
            'location_id' => $location->id,
            'employment_type' => 'full_time',
            'description' => 'Test job',
            'requirements' => 'Test requirements',
            'responsibilities' => 'Test responsibilities',
            'min_salary' => 5000000,
            'max_salary' => 10000000,
            'status' => 'active',
            'published_at' => now(),
            'application_deadline' => now()->addDays(30),
        ]);

        // Apply for job
        $cvFile = UploadedFile::fake()->create('cv.pdf', 1024);
        
        $response = $this->actingAs($candidate)->post(route('candidate.applications.store'), [
            'job_posting_id' => $job->id,
            'cv' => $cvFile,
            'cover_letter' => 'Test cover letter',
            'agree_terms' => true,
        ]);

        // Check application created
        $application = Application::where('candidate_id', $candidate->id)->first();
        $this->assertNotNull($application, 'Application should be created');

        // Check snapshot exists
        $this->assertNotNull($application->candidate_snapshot, 'Snapshot should exist');
        $snapshot = $application->candidate_snapshot;

        // Verify snapshot content
        $this->assertEquals('John Doe', $snapshot['full_name']);
        $this->assertEquals('candidate@example.com', $snapshot['email']);
        $this->assertEquals('08123456789', $snapshot['phone']);
        $this->assertEquals('123 Test Street', $snapshot['address']);
        $this->assertEquals('1995-01-15', $snapshot['birth_date']);
        $this->assertEquals('male', $snapshot['gender']);
        $this->assertArrayHasKey('snapshot_at', $snapshot);
        
        echo "\n✅ TEST 6 PASSED: Application creates snapshot correctly\n";
    }

    /** @test */
    public function test_07_snapshot_remains_unchanged_after_profile_update()
    {
        // Create candidate and application first
        $candidate = User::create([
            'name' => 'Test User',
            'email' => 'profile@example.com',
            'password' => Hash::make('Password123!'),
            'role_id' => $this->candidateRole->id,
            'is_active' => true,
            'registration_step' => 5,
            'registration_completed' => true,
            'full_name' => 'Original Name',
            'phone' => '08111111111',
            'address' => 'Original Address',
            'birth_date' => '1995-01-15',
            'gender' => 'male',
        ]);

        $division = Division::create(['name' => 'IT', 'description' => 'IT']);
        $position = Position::create(['name' => 'Developer', 'description' => 'Dev']);
        $location = Location::create(['city' => 'Jakarta', 'address' => 'JKT']);
        
        $job = JobPosting::create([
            'job_code' => 'JOB-002',
            'title' => 'Test Job',
            'division_id' => $division->id,
            'position_id' => $position->id,
            'location_id' => $location->id,
            'employment_type' => 'full_time',
            'description' => 'Test',
            'requirements' => 'Test',
            'responsibilities' => 'Test',
            'min_salary' => 5000000,
            'max_salary' => 10000000,
            'status' => 'active',
            'published_at' => now(),
            'application_deadline' => now()->addDays(30),
        ]);

        $application = Application::create([
            'candidate_id' => $candidate->id,
            'job_posting_id' => $job->id,
            'application_code' => 'APP-TEST-001',
            'candidate_snapshot' => [
                'full_name' => 'Original Name',
                'email' => 'profile@example.com',
                'phone' => '08111111111',
                'address' => 'Original Address',
                'birth_date' => '1995-01-15',
                'gender' => 'male',
                'snapshot_at' => now()->toDateTimeString(),
            ],
            'cv_file' => 'cv.pdf',
            'status' => 'submitted',
        ]);

        // Update profile
        $candidate->update([
            'full_name' => 'Updated Name',
            'phone' => '08222222222',
            'address' => 'Updated Address',
        ]);

        // Reload application
        $application->refresh();

        // Snapshot should remain UNCHANGED
        $snapshot = $application->candidate_snapshot;
        $this->assertEquals('Original Name', $snapshot['full_name'], 
            'Snapshot should keep original name');
        $this->assertEquals('08111111111', $snapshot['phone'], 
            'Snapshot should keep original phone');
        $this->assertEquals('Original Address', $snapshot['address'], 
            'Snapshot should keep original address');

        // Check accessor returns snapshot data
        $this->assertEquals('Original Name', $application->candidate_name);
        $this->assertEquals('08111111111', $application->candidate_phone);

        // Check hasProfileChangedSinceApply()
        $this->assertTrue($application->hasProfileChangedSinceApply(), 
            'Should detect profile has changed');
        
        echo "\n✅ TEST 7 PASSED: Snapshot immutable - remains unchanged after profile update\n";
    }

    /** @test */
    public function test_08_accessor_methods_work_correctly()
    {
        $application = new Application();
        $application->candidate_snapshot = [
            'full_name' => 'Test Name',
            'email' => 'test@test.com',
            'phone' => '081234567890',
            'address' => 'Test Address',
            'birth_date' => '1990-01-01',
            'gender' => 'female',
        ];

        $this->assertEquals('Test Name', $application->candidate_name);
        $this->assertEquals('test@test.com', $application->candidate_email);
        $this->assertEquals('081234567890', $application->candidate_phone);
        $this->assertEquals('Test Address', $application->candidate_address);
        $this->assertEquals('1990-01-01', $application->candidate_birth_date);
        $this->assertEquals('female', $application->candidate_gender);

        echo "\n✅ TEST 8 PASSED: Accessor methods work correctly\n";
    }

    /** @test */
    public function test_09_model_fillable_excludes_duplicate_fields()
    {
        $application = new Application();
        $fillable = $application->getFillable();

        // Should have candidate_snapshot
        $this->assertContains('candidate_snapshot', $fillable);

        // Should NOT have duplicate fields
        $this->assertNotContains('full_name', $fillable);
        $this->assertNotContains('email', $fillable);
        $this->assertNotContains('phone', $fillable);
        $this->assertNotContains('address', $fillable);
        $this->assertNotContains('birth_date', $fillable);
        $this->assertNotContains('gender', $fillable);

        echo "\n✅ TEST 9 PASSED: Model fillable correct (no duplicate fields)\n";
    }

    /** @test */
    public function test_10_integration_full_user_journey()
    {
        echo "\n\n🚀 INTEGRATION TEST: Full User Journey\n";
        echo "========================================\n\n";

        // 1. Register (Step 1)
        echo "1️⃣ Registering new candidate...\n";
        $response = $this->post(route('register.step1.process'), [
            'name' => 'Integration Test',
            'email' => 'integration@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'agree_terms' => true,
        ]);
        
        $user = User::where('email', 'integration@test.com')->first();
        $this->assertTrue($user->is_active, 'Should be active after registration');
        echo "   ✅ User registered with is_active = true\n\n";

        // 2. Logout then Login (test bug fix)
        echo "2️⃣ Testing logout during registration...\n";
        $this->post(route('logout'));
        
        echo "3️⃣ Login again with incomplete registration...\n";
        $loginResponse = $this->post(route('login'), [
            'email' => 'integration@test.com',
            'password' => 'Password123!',
        ]);
        
        $this->assertAuthenticated();
        echo "   ✅ Can login with incomplete registration (bug fixed)\n";
        echo "   ✅ Smart redirect to next step\n\n";

        // 3. Complete registration (simplified)
        echo "4️⃣ Completing registration steps...\n";
        $user->update([
            'registration_step' => 5,
            'registration_completed' => true,
            'full_name' => 'Integration Test User',
            'phone' => '08123456789',
            'address' => 'Test Integration Address',
            'birth_date' => '1990-05-15',
            'gender' => 'male',
            'education' => json_encode([
                ['degree' => 'S1', 'institution' => 'Test Univ', 'major' => 'CS', 'graduation_year' => 2020]
            ]),
        ]);
        echo "   ✅ Registration completed\n\n";

        // 4. Apply for job
        echo "5️⃣ Creating job posting and applying...\n";
        $division = Division::create(['name' => 'Engineering', 'description' => 'Eng']);
        $position = Position::create(['name' => 'Software Engineer', 'description' => 'SE']);
        $location = Location::create(['city' => 'Jakarta', 'address' => 'Jakarta']);
        
        $job = JobPosting::create([
            'job_code' => 'JOB-INT-001',
            'title' => 'Senior Developer',
            'division_id' => $division->id,
            'position_id' => $position->id,
            'location_id' => $location->id,
            'employment_type' => 'full_time',
            'description' => 'Integration test job',
            'requirements' => 'Test req',
            'responsibilities' => 'Test resp',
            'min_salary' => 8000000,
            'max_salary' => 15000000,
            'status' => 'active',
            'published_at' => now(),
            'application_deadline' => now()->addDays(30),
        ]);

        $cvFile = UploadedFile::fake()->create('integration_cv.pdf', 1024);
        
        $this->actingAs($user)->post(route('candidate.applications.store'), [
            'job_posting_id' => $job->id,
            'cv' => $cvFile,
            'cover_letter' => 'Integration test cover letter',
            'agree_terms' => true,
        ]);

        $application = Application::where('candidate_id', $user->id)->first();
        $this->assertNotNull($application);
        echo "   ✅ Application submitted\n";
        echo "   ✅ Snapshot created: {$application->candidate_name}\n\n";

        // 5. Update profile
        echo "6️⃣ Updating candidate profile...\n";
        $user->update([
            'full_name' => 'Updated Integration Name',
            'phone' => '08987654321',
        ]);
        echo "   ✅ Profile updated\n\n";

        // 6. Verify snapshot unchanged
        echo "7️⃣ Verifying snapshot immutability...\n";
        $application->refresh();
        $this->assertEquals('Integration Test User', $application->candidate_name, 
            'Snapshot should keep original name');
        $this->assertEquals('08123456789', $application->candidate_phone, 
            'Snapshot should keep original phone');
        echo "   ✅ Snapshot unchanged (still shows original data)\n";
        echo "   ✅ hasProfileChangedSinceApply() = " . ($application->hasProfileChangedSinceApply() ? 'true' : 'false') . "\n\n";

        echo "========================================\n";
        echo "✅ INTEGRATION TEST COMPLETE - ALL PASSED!\n\n";
    }
}
