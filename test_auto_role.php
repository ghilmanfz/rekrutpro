<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING AUTO ROLE ASSIGNMENT ===\n\n";

// Create a test user without role_id
echo "Creating test user without role_id...\n";

try {
    $user = \App\Models\User::create([
        'name' => 'Test Auto Role',
        'email' => 'testrole' . time() . '@example.com',
        'password' => bcrypt('password123'),
        'registration_step' => 1,
        'registration_completed' => false,
        'is_active' => false,
        'is_verified' => false,
        // NOTE: role_id is NOT set here - should be auto-assigned
    ]);
    
    // Refresh to get latest data
    $user->refresh();
    
    echo "✓ User created successfully\n";
    echo "  ID: {$user->id}\n";
    echo "  Email: {$user->email}\n";
    echo "  Role ID: " . ($user->role_id ?? 'NULL') . "\n";
    
    if ($user->role_id) {
        echo "\n✓ SUCCESS: Role was auto-assigned!\n";
        echo "  Role Name: {$user->role->name}\n";
    } else {
        echo "\n❌ FAILED: Role was NOT assigned!\n";
    }
    
    // Cleanup
    echo "\nCleaning up test user...\n";
    $user->delete();
    echo "✓ Test user deleted\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
