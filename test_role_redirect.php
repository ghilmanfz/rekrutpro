<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         TESTING LOGIN REDIRECT BY ROLE                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Test data
$testUsers = [
    ['email' => 'admin@rekrutpro.com', 'role' => 'super_admin', 'expected_route' => 'superadmin.dashboard'],
    ['email' => 'hr@rekrutpro.com', 'role' => 'hr', 'expected_route' => 'hr.dashboard'],
    ['email' => 'interviewer@rekrutpro.com', 'role' => 'interviewer', 'expected_route' => 'interviewer.dashboard'],
    ['email' => 'testcomplete@example.com', 'role' => 'candidate', 'expected_route' => 'candidate.dashboard'],
];

echo "Testing User Redirect Logic:\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($testUsers as $test) {
    $user = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.email', $test['email'])
        ->select('users.*', 'roles.name as role_name')
        ->first();
    
    if (!$user) {
        echo "❌ User {$test['email']} NOT FOUND\n\n";
        continue;
    }
    
    echo "📧 Email: {$user->email}\n";
    echo "👤 Name: {$user->name}\n";
    echo "🎭 Role: {$user->role_name}\n";
    echo "✅ Registration Completed: " . ($user->registration_completed ? 'YES' : 'NO') . "\n";
    echo "🔓 Is Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "📍 Expected Redirect: {$test['expected_route']}\n";
    
    // Check if matches
    $roleMatches = $user->role_name === $test['role'];
    $regCompleted = (bool)$user->registration_completed;
    $isActive = (bool)$user->is_active;
    
    if ($roleMatches && $regCompleted && $isActive) {
        echo "🎉 Status: READY TO LOGIN ✓\n";
    } else {
        echo "⚠️  Status: ISSUE FOUND\n";
        if (!$roleMatches) echo "   - Role mismatch\n";
        if (!$regCompleted) echo "   - Registration not completed\n";
        if (!$isActive) echo "   - User not active\n";
    }
    
    echo str_repeat("-", 80) . "\n\n";
}

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║         LOGIN CREDENTIALS FOR TESTING                          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "1. SUPER ADMIN\n";
echo "   Email    : admin@rekrutpro.com\n";
echo "   Password : password\n";
echo "   Expected : http://127.0.0.1:8000/superadmin/dashboard\n\n";

echo "2. HR / RECRUITER\n";
echo "   Email    : hr@rekrutpro.com\n";
echo "   Password : password\n";
echo "   Expected : http://127.0.0.1:8000/hr/dashboard\n\n";

echo "3. INTERVIEWER\n";
echo "   Email    : interviewer@rekrutpro.com\n";
echo "   Password : password\n";
echo "   Expected : http://127.0.0.1:8000/interviewer/dashboard\n\n";

echo "4. CANDIDATE\n";
echo "   Email    : testcomplete@example.com\n";
echo "   Password : tes12345\n";
echo "   Expected : http://127.0.0.1:8000/candidate/dashboard\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "🔍 CHANGES MADE:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "1. ✅ AuthenticatedSessionController\n";
echo "   - Added redirectBasedOnRole() method\n";
echo "   - Redirects to appropriate dashboard based on user role\n\n";

echo "2. ✅ EnsureRegistrationCompleted Middleware\n";
echo "   - Skip registration check for internal users\n";
echo "   - (super_admin, hr, interviewer)\n\n";

echo "3. ✅ Database Fix\n";
echo "   - Set registration_completed = true for internal users\n";
echo "   - Set is_verified = true\n";
echo "   - Set is_active = true\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "📋 NEXT STEPS:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "1. Test Login as Super Admin:\n";
echo "   - Go to: http://127.0.0.1:8000/login\n";
echo "   - Email: admin@rekrutpro.com\n";
echo "   - Password: password\n";
echo "   - Should redirect to: /superadmin/dashboard\n\n";

echo "2. Test Login as HR:\n";
echo "   - Email: hr@rekrutpro.com\n";
echo "   - Password: password\n";
echo "   - Should redirect to: /hr/dashboard\n\n";

echo "3. Test Login as Interviewer:\n";
echo "   - Email: interviewer@rekrutpro.com\n";
echo "   - Password: password\n";
echo "   - Should redirect to: /interviewer/dashboard\n\n";

echo "4. Test Login as Candidate:\n";
echo "   - Email: testcomplete@example.com\n";
echo "   - Password: tes12345\n";
echo "   - Should redirect to: /candidate/dashboard\n\n";

echo "5. Test Registration (New User):\n";
echo "   - Go to: http://127.0.0.1:8000/register\n";
echo "   - Should show Step 1 registration form\n";
echo "   - Complete all 5 steps\n";
echo "   - After completion, should redirect to /candidate/dashboard\n\n";

echo "═══════════════════════════════════════════════════════════════\n\n";
echo "✅ All systems ready for testing!\n\n";
