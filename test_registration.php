<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING NEW REGISTRATION ===\n\n";

// Check if testcomplete@example.com exists
$email = 'testcomplete@example.com';
echo "Checking user: {$email}\n\n";

$user = DB::table('users')->where('email', $email)->first();

if ($user) {
    echo "✓ USER FOUND\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ID               : {$user->id}\n";
    echo "Name             : {$user->name}\n";
    echo "Email            : {$user->email}\n";
    echo "Role ID          : " . ($user->role_id ?? '❌ NULL') . "\n";
    echo "Registration Step: {$user->registration_step}\n";
    echo "Reg. Completed   : " . ($user->registration_completed ? '✓ YES' : '❌ NO') . "\n";
    echo "Is Active        : " . ($user->is_active ? '✓ YES' : '❌ NO') . "\n";
    echo "Is Verified      : " . ($user->is_verified ? '✓ YES' : '❌ NO') . "\n";
    echo "CV Path          : " . ($user->cv_path ?? '(none)') . "\n";
    echo "Phone            : " . ($user->phone ?? '(none)') . "\n";
    echo "Education        : " . ($user->education ?? '(none)') . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Verify role
    if ($user->role_id) {
        $role = DB::table('roles')->where('id', $user->role_id)->first();
        echo "✓ ROLE VERIFICATION\n";
        echo "  Role Name: {$role->name}\n";
        echo "  Display  : {$role->display_name}\n\n";
    } else {
        echo "❌ ERROR: Role not assigned!\n\n";
    }
    
    // Check if ready to access dashboard
    echo "DASHBOARD ACCESS CHECK:\n";
    if ($user->role_id == 4 && $user->registration_completed && $user->is_active) {
        echo "  ✓ All checks passed - CAN ACCESS DASHBOARD\n";
    } else {
        echo "  ❌ Cannot access dashboard:\n";
        if (!$user->role_id) echo "     - Role not assigned\n";
        if ($user->role_id != 4) echo "     - Not a candidate role\n";
        if (!$user->registration_completed) echo "     - Registration not completed\n";
        if (!$user->is_active) echo "     - User not active\n";
    }
    
} else {
    echo "❌ USER NOT FOUND\n";
    echo "Registration has not been completed yet or user does not exist.\n";
}

echo "\n=== TEST COMPLETED ===\n";
