<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║           CHECKING EXISTING JOB POSTINGS                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$jobs = DB::table('job_postings')->get();

echo "Total job postings: {$jobs->count()}\n";
echo str_repeat("─", 60) . "\n\n";

if ($jobs->count() > 0) {
    foreach ($jobs as $job) {
        echo "ID: {$job->id}\n";
        echo "Code: {$job->code}\n";
        echo "Title: {$job->title}\n";
        echo "Status: {$job->status}\n";
        echo "Position ID: {$job->position_id}\n";
        echo str_repeat("-", 40) . "\n";
    }
    
    echo "\n🧹 CLEANUP OPTIONS:\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "If these are test data, you can delete them with:\n";
    echo "php artisan tinker\n";
    echo ">>> DB::table('job_postings')->truncate();\n\n";
} else {
    echo "✅ No job postings found. Database is clean.\n\n";
}

echo "═══════════════════════════════════════════════════════════\n";
echo "Testing new code generation...\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// Test code generation for position 1 (Software Engineer)
$position = DB::table('positions')->where('id', 1)->first();

if ($position) {
    echo "Position: {$position->name} (Code: {$position->code})\n";
    
    $prefix = strtoupper($position->code);
    echo "Prefix: {$prefix}\n";
    
    $lastJob = DB::table('job_postings')
        ->where('code', 'like', $prefix . '-%')
        ->orderByRaw('CAST(SUBSTRING_INDEX(code, "-", -1) AS UNSIGNED) DESC')
        ->first();
    
    if ($lastJob) {
        $parts = explode('-', $lastJob->code);
        $lastNumber = (int) end($parts);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        echo "Last Job Code: {$lastJob->code}\n";
        echo "Last Number: {$lastNumber}\n";
    } else {
        $newNumber = '001';
        echo "No previous jobs found.\n";
    }
    
    $newCode = $prefix . '-' . $newNumber;
    echo "Next Code: {$newCode}\n";
    
    // Check if exists
    $exists = DB::table('job_postings')->where('code', $newCode)->exists();
    echo "Code Exists: " . ($exists ? 'YES ❌' : 'NO ✓') . "\n";
}
