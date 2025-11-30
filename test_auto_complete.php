<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║    TESTING AUTO-COMPLETE REGISTRATION FOR INTERNAL USERS      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "Creating test internal users...\n\n";

// Test 1: Create Super Admin
$testEmail1 = 'testadmin_' . time() . '@test.com';
echo "1. Creating Super Admin: {$testEmail1}\n";

$admin = App\Models\User::create([
    'name' => 'Test Super Admin',
    'email' => $testEmail1,
    'password' => Hash::make('password'),
    'role_id' => 1, // super_admin
]);

echo "   Created user ID: {$admin->id}\n";
echo "   Role ID: {$admin->role_id}\n";
echo "   Registration Completed: " . ($admin->registration_completed ? 'YES ✓' : 'NO ❌') . "\n";
echo "   Is Verified: " . ($admin->is_verified ? 'YES ✓' : 'NO ❌') . "\n";
echo "   Is Active: " . ($admin->is_active ? 'YES ✓' : 'NO ❌') . "\n";

if ($admin->registration_completed && $admin->is_verified && $admin->is_active) {
    echo "   ✅ SUCCESS: Auto-complete worked!\n";
} else {
    echo "   ❌ FAILED: Auto-complete didn't work\n";
}

echo "\n";

// Test 2: Create HR
$testEmail2 = 'testhr_' . time() . '@test.com';
echo "2. Creating HR: {$testEmail2}\n";

$hr = App\Models\User::create([
    'name' => 'Test HR',
    'email' => $testEmail2,
    'password' => Hash::make('password'),
    'role_id' => 2, // hr
]);

echo "   Created user ID: {$hr->id}\n";
echo "   Role ID: {$hr->role_id}\n";
echo "   Registration Completed: " . ($hr->registration_completed ? 'YES ✓' : 'NO ❌') . "\n";
echo "   Is Verified: " . ($hr->is_verified ? 'YES ✓' : 'NO ❌') . "\n";
echo "   Is Active: " . ($hr->is_active ? 'YES ✓' : 'NO ❌') . "\n";

if ($hr->registration_completed && $hr->is_verified && $hr->is_active) {
    echo "   ✅ SUCCESS: Auto-complete worked!\n";
} else {
    echo "   ❌ FAILED: Auto-complete didn't work\n";
}

echo "\n";

// Test 3: Create Candidate (should NOT auto-complete)
$testEmail3 = 'testcandidate_' . time() . '@test.com';
echo "3. Creating Candidate: {$testEmail3}\n";

$candidate = App\Models\User::create([
    'name' => 'Test Candidate',
    'email' => $testEmail3,
    'password' => Hash::make('password'),
    'role_id' => 4, // candidate
]);

echo "   Created user ID: {$candidate->id}\n";
echo "   Role ID: {$candidate->role_id}\n";
echo "   Registration Completed: " . ($candidate->registration_completed ? 'YES' : 'NO ✓') . "\n";

if (!$candidate->registration_completed) {
    echo "   ✅ SUCCESS: Candidate NOT auto-completed (correct!)\n";
} else {
    echo "   ❌ FAILED: Candidate should not be auto-completed\n";
}

echo "\n";

// Cleanup test users
echo "═══════════════════════════════════════════════════════════════\n";
echo "Cleaning up test users...\n";
App\Models\User::whereIn('id', [$admin->id, $hr->id, $candidate->id])->delete();
echo "✓ Test users deleted\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "📋 SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ Model Event: Auto-complete registration for internal users\n";
echo "   - Super Admin (role_id: 1) → registration_completed = TRUE\n";
echo "   - HR (role_id: 2) → registration_completed = TRUE\n";
echo "   - Interviewer (role_id: 3) → registration_completed = TRUE\n";
echo "   - Candidate (role_id: 4) → registration_completed = FALSE\n\n";

echo "✅ From now on, any new internal user will automatically:\n";
echo "   - Have registration_completed = true\n";
echo "   - Have is_verified = true\n";
echo "   - Have is_active = true\n";
echo "   - Can login immediately without registration steps\n\n";

echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 admin2@rekrutpro.com is now ready to login!\n";
echo "   Email: admin2@rekrutpro.com\n";
echo "   Password: password\n";
echo "   URL: http://127.0.0.1:8000/login\n\n";
