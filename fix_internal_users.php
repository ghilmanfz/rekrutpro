<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING INTERNAL USERS REGISTRATION STATUS ===\n\n";

// Update Super Admin, HR, Interviewer to have registration_completed = true
$updated = DB::table('users')
    ->whereIn('role_id', [1, 2, 3]) // super_admin, hr, interviewer
    ->update([
        'registration_completed' => true,
        'is_verified' => true,
        'is_active' => true,
    ]);

echo "Updated {$updated} internal user(s)\n\n";

echo "=== VERIFICATION ===\n\n";

$users = DB::table('users')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->whereIn('users.role_id', [1, 2, 3])
    ->select('users.email', 'roles.name as role', 'users.registration_completed', 'users.is_verified', 'users.is_active')
    ->get();

foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Registration Completed: " . ($user->registration_completed ? 'YES' : 'NO') . "\n";
    echo "Verified: " . ($user->is_verified ? 'YES' : 'NO') . "\n";
    echo "Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "---\n";
}

echo "\n✅ All internal users are now ready to login!\n";
