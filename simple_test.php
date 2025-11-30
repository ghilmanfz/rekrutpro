<?php

// Simple test script
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Testing User Model Event...\n\n";

// Create Super Admin
$email = 'testadmin' . time() . '@test.com';
echo "Creating Super Admin: {$email}\n";

try {
    $user = User::create([
        'name' => 'Test Super Admin',
        'email' => $email,
        'password' => Hash::make('password'),
        'role_id' => 1,
    ]);
    
    echo "Created user ID: {$user->id}\n";
    echo "Registration Completed: " . ($user->registration_completed ? 'YES ✓' : 'NO ❌') . "\n";
    echo "Is Verified: " . ($user->is_verified ? 'YES ✓' : 'NO ❌') . "\n";
    echo "Is Active: " . ($user->is_active ? 'YES ✓' : 'NO ❌') . "\n\n";
    
    if ($user->registration_completed && $user->is_verified && $user->is_active) {
        echo "✅ SUCCESS: Auto-complete worked!\n\n";
    } else {
        echo "❌ FAILED: Auto-complete didn't work\n\n";
    }
    
    // Cleanup
    $user->delete();
    echo "Cleaned up test user\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
