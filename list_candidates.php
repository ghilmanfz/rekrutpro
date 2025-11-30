<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "═══════════════════════════════════════════════════════════\n";
echo "  CANDIDATE USERS FOR TESTING                               \n";
echo "═══════════════════════════════════════════════════════════\n\n";

$candidates = User::whereHas('role', function($q) {
    $q->where('name', 'candidate');
})->get();

echo "Total candidates: " . $candidates->count() . "\n\n";

foreach ($candidates as $user) {
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Registration Completed: " . ($user->registration_completed ? 'YES' : 'NO') . "\n";
    echo "Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "Verified: " . ($user->is_verified ? 'YES' : 'NO') . "\n";
    echo "Created: {$user->created_at}\n";
    echo "---\n";
}

echo "\n💡 Test dengan salah satu candidate di atas.\n";
echo "Password untuk semua user test: 'password'\n";
