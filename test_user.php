<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING USER DATA ===\n\n";

// Test 1: Check user adada@ada.com
echo "1. Checking user adada@ada.com:\n";
$user = DB::table('users')->where('email', 'adada@ada.com')->first();
if ($user) {
    echo "   ✓ User found\n";
    echo "   - ID: {$user->id}\n";
    echo "   - Name: {$user->name}\n";
    echo "   - Email: {$user->email}\n";
    echo "   - Role ID: " . ($user->role_id ?? 'NULL') . "\n";
    echo "   - Registration Step: {$user->registration_step}\n";
    echo "   - Registration Completed: " . ($user->registration_completed ? 'YES' : 'NO') . "\n";
    echo "   - Is Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "   - Is Verified: " . ($user->is_verified ? 'YES' : 'NO') . "\n";
} else {
    echo "   ✗ User NOT found\n";
}

echo "\n2. Checking role 'candidate':\n";
$role = DB::table('roles')->where('name', 'candidate')->first();
if ($role) {
    echo "   ✓ Role found\n";
    echo "   - ID: {$role->id}\n";
    echo "   - Name: {$role->name}\n";
    echo "   - Display Name: {$role->display_name}\n";
} else {
    echo "   ✗ Role NOT found\n";
}

echo "\n3. All users in system:\n";
$users = DB::table('users')->get(['id', 'name', 'email', 'role_id', 'registration_completed']);
foreach ($users as $u) {
    echo "   - ID {$u->id}: {$u->name} ({$u->email}) - Role: " . ($u->role_id ?? 'NULL') . " - Completed: " . ($u->registration_completed ? 'YES' : 'NO') . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
