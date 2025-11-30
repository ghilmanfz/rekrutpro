<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════════════\n";
echo "  FIXING JOB POSTINGS WITHOUT DEADLINE                      \n";
echo "═══════════════════════════════════════════════════════════\n\n";

try {
    // Get jobs without closed_at
    $jobs = JobPosting::whereNull('closed_at')->get();
    
    echo "Found {$jobs->count()} jobs without deadline\n\n";
    
    if ($jobs->isEmpty()) {
        echo "All jobs have deadline.\n";
        exit(0);
    }
    
    DB::beginTransaction();
    
    foreach ($jobs as $job) {
        if ($job->published_at) {
            $job->closed_at = \Carbon\Carbon::parse($job->published_at)->addDays(30);
        } else {
            $job->closed_at = now()->addDays(30);
        }
        $job->save();
        
        echo "✓ Updated: {$job->code} - {$job->title}\n";
        echo "  Deadline set to: {$job->closed_at}\n\n";
    }
    
    DB::commit();
    
    echo "═══════════════════════════════════════════════════════════\n";
    echo "✅ All jobs have deadline now!\n";
    echo "═══════════════════════════════════════════════════════════\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}
