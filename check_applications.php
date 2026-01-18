<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Application;

echo "=== Checking Applications ===\n\n";

$applications = Application::with('user')->get();

if ($applications->isEmpty()) {
    echo "❌ Tidak ada aplikasi ditemukan.\n";
} else {
    foreach ($applications as $app) {
        echo "Application ID: {$app->id}\n";
        echo "User: " . ($app->user ? $app->user->name : 'No user') . "\n";
        echo "Job ID: {$app->job_id}\n";
        echo "Status: {$app->status}\n";
        echo "---\n";
    }
}
