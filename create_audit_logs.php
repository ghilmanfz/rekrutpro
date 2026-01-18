<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Application;

// Create sample audit logs
$user = User::first();
$application = Application::first();

if (!$user) {
    echo "❌ User tidak ditemukan. Silakan seed data terlebih dahulu.\n";
    exit;
}

// 1. Login log
AuditLog::create([
    'user_id' => $user->id,
    'action' => 'user_login',
    'model_type' => 'App\Models\User',
    'model_id' => $user->id,
    'old_values' => null,
    'new_values' => [
        'name' => $user->name,
        'email' => $user->email,
    ],
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
]);

echo "✅ Created user_login log\n";

// 2. Update application status
if ($application) {
    // Dapatkan nama kandidat yang sebenarnya
    $candidateName = $application->candidate_name; // Gunakan accessor
    
    AuditLog::create([
        'user_id' => $user->id,
        'action' => 'application_status_updated',
        'model_type' => 'App\Models\Application',
        'model_id' => $application->id,
        'old_values' => [
            'status' => 'submitted',
            'candidate_name' => $candidateName,
            'job_title' => $application->jobPosting->title ?? 'N/A',
        ],
        'new_values' => [
            'status' => 'screening_passed',
            'candidate_name' => $candidateName,
            'job_title' => $application->jobPosting->title ?? 'N/A',
        ],
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ]);
    
    echo "✅ Created application_status_updated log for: {$candidateName}\n";
}

// 3. User created
AuditLog::create([
    'user_id' => $user->id,
    'action' => 'user_created',
    'model_type' => 'App\Models\User',
    'model_id' => $user->id,
    'old_values' => null,
    'new_values' => [
        'name' => 'Alice Smith',
        'email' => 'alice.smith@example.com',
        'role' => 'Kandidat',
    ],
    'ip_address' => '192.168.1.100',
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
]);

echo "✅ Created user_created log\n";

// 4. Job application submitted
if ($application) {
    $candidateName = $application->candidate_name;
    
    AuditLog::create([
        'user_id' => $user->id,
        'action' => 'application_submitted',
        'model_type' => 'App\Models\Application',
        'model_id' => $application->id,
        'old_values' => null,
        'new_values' => [
            'job_title' => $application->jobPosting->title ?? 'Software Engineer',
            'candidate_name' => $candidateName,
            'status' => 'submitted',
        ],
        'ip_address' => '10.0.0.50',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)',
    ]);
    
    echo "✅ Created application_submitted log for: {$candidateName}\n";
}

echo "\n✅ Berhasil membuat " . AuditLog::count() . " audit logs!\n";
echo "🌐 Silakan refresh halaman /superadmin/audit\n";
