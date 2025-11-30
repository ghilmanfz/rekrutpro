<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING INCOMPLETE REGISTRATION ===\n\n";

// Check if testincomplete@example.com exists
$email = 'testincomplete@example.com';
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
    echo "Reg. Completed   : " . ($user->registration_completed ? '✓ YES' : '❌ NO (Expected)') . "\n";
    echo "Is Active        : " . ($user->is_active ? '✓ YES' : '❌ NO (Expected)') . "\n";
    echo "Is Verified      : " . ($user->is_verified ? '✓ YES' : '❌ NO') . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "LOGIN ATTEMPT CHECK:\n";
    if (!$user->registration_completed) {
        echo "  ✓ CORRECT: User should be BLOCKED from login\n";
        echo "  Expected behavior: Login fails with error message\n";
    } else {
        echo "  ❌ ERROR: User registration is marked as completed!\n";
    }
    
    echo "\nREDIRECT CHECK:\n";
    $step = $user->registration_step ?? 1;
    $nextRoute = match($step) {
        1 => '/register/step2',
        2 => '/register/step3',
        3 => '/register/step4',
        4 => '/register/step5',
        default => '/register/step2'
    };
    echo "  Current Step: {$step}\n";
    echo "  Should redirect to: {$nextRoute}\n";
    
} else {
    echo "❌ USER NOT FOUND\n";
    echo "Test user has not been created yet.\n";
}

echo "\n=== TEST COMPLETED ===\n";
