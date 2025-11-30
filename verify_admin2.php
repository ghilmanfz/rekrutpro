<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              VERIFICATION: admin2@rekrutpro.com                ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$admin2 = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.email', 'admin2@rekrutpro.com')
    ->select('users.*', 'roles.name as role_name')
    ->first();

if (!$admin2) {
    echo "❌ User NOT FOUND!\n";
    exit;
}

echo "📧 Email: {$admin2->email}\n";
echo "👤 Name: {$admin2->name}\n";
echo "🎭 Role: {$admin2->role_name}\n";
echo str_repeat("─", 60) . "\n\n";

$checks = [
    'Role ID = 1 (super_admin)' => $admin2->role_id == 1,
    'Role Name = super_admin' => $admin2->role_name === 'super_admin',
    'Registration Completed = TRUE' => (bool)$admin2->registration_completed,
    'Is Verified = TRUE' => (bool)$admin2->is_verified,
    'Is Active = TRUE' => (bool)$admin2->is_active,
];

echo "🔍 PRE-LOGIN CHECKS:\n\n";
$allPassed = true;

foreach ($checks as $check => $result) {
    $status = $result ? '✅' : '❌';
    $value = $result ? 'PASS' : 'FAIL';
    echo "   {$status} {$check} → {$value}\n";
    if (!$result) $allPassed = false;
}

echo "\n" . str_repeat("═", 60) . "\n\n";

if ($allPassed) {
    echo "🎉 ALL CHECKS PASSED!\n\n";
    echo "✅ admin2@rekrutpro.com is ready to login!\n\n";
    echo "📋 LOGIN INSTRUCTIONS:\n";
    echo "   1. Open browser → http://127.0.0.1:8000/login\n";
    echo "   2. Email: admin2@rekrutpro.com\n";
    echo "   3. Password: password\n";
    echo "   4. Click 'Masuk'\n";
    echo "   5. Expected: Redirect to /superadmin/dashboard\n\n";
    echo "🔐 WHAT HAPPENS WHEN YOU LOGIN:\n";
    echo "   → Authenticate user ✓\n";
    echo "   → Check registration_completed (TRUE) ✓\n";
    echo "   → Check user role (super_admin) ✓\n";
    echo "   → Redirect to /superadmin/dashboard ✓\n";
} else {
    echo "⚠️  SOME CHECKS FAILED!\n\n";
    echo "Please run: php fix_admin2.php\n";
}

echo "\n" . str_repeat("═", 60) . "\n\n";

echo "📊 ALL INTERNAL USERS STATUS:\n\n";

$internalUsers = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('users.role_id', [1, 2, 3])
    ->select('users.email', 'roles.name as role', 'users.registration_completed', 'users.is_active')
    ->get();

foreach ($internalUsers as $user) {
    $regStatus = $user->registration_completed ? '✅' : '❌';
    $activeStatus = $user->is_active ? '✅' : '❌';
    
    echo "   {$user->email}\n";
    echo "   └─ Role: {$user->role} | Reg: {$regStatus} | Active: {$activeStatus}\n\n";
}

echo str_repeat("═", 60) . "\n\n";
