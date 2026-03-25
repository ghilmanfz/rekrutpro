<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Update Kandidat Registration Status ===\n\n";

$candidates = User::whereHas('role', function($q) {
    $q->where('name', 'candidate');
})->get();

foreach ($candidates as $user) {
    $user->registration_step = 6; // Set ke 6 (selesai)
    $user->registration_completed = true;
    $user->save();
    
    echo "✅ Updated: {$user->name} - Registration Completed\n";
}

echo "\n✅ Semua kandidat berhasil di-update!\n";
echo "Sekarang kandidat bisa login tanpa error.\n";
