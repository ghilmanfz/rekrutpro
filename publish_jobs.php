<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════════════\n";
echo "  PUBLISHING JOB POSTINGS                                   \n";
echo "═══════════════════════════════════════════════════════════\n\n";

try {
    // Get all draft jobs
    $draftJobs = JobPosting::where('status', 'draft')->get();
    
    echo "Found {$draftJobs->count()} draft jobs\n\n";
    
    if ($draftJobs->isEmpty()) {
        echo "No draft jobs to publish.\n";
        exit(0);
    }
    
    DB::beginTransaction();
    
    foreach ($draftJobs as $job) {
        $job->status = 'active';
        $job->published_at = now();
        $job->closed_at = now()->addDays(30); // 30 days from now
        $job->save();
        
        echo "✓ Published: {$job->code} - {$job->title}\n";
        echo "  Published at: {$job->published_at}\n";
        echo "  Closes at: {$job->closed_at}\n\n";
    }
    
    DB::commit();
    
    echo "═══════════════════════════════════════════════════════════\n";
    echo "✅ All jobs published successfully!\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    // Show all active jobs
    echo "Active job postings:\n";
    $activeJobs = JobPosting::where('status', 'active')
        ->with('position')
        ->orderBy('published_at', 'desc')
        ->get();
    
    foreach ($activeJobs as $job) {
        echo "  - {$job->code}: {$job->title} ({$job->position->name})\n";
        echo "    Status: {$job->status}\n";
        echo "    Published: {$job->published_at}\n";
        echo "    Deadline: {$job->closed_at}\n\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
