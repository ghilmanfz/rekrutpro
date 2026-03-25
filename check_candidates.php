<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Cek User Kandidat ===\n\n";

$candidates = User::whereHas('role', function($q) {
    $q->where('name', 'candidate');
})->get();

echo "Total kandidat: " . $candidates->count() . "\n\n";

foreach ($candidates as $user) {
    echo "ID: {$user->id}\n";
    echo "Nama: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Registration Step: {$user->registration_step}\n";
    echo "Registration Completed: " . ($user->registration_completed ? 'Yes' : 'No') . "\n";
    echo "Is Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
