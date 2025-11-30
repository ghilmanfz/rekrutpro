<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;

echo "=== Checking Job Postings (Without Soft Deletes) ===\n\n";

// Check via DB::table (includes soft deleted)
$allJobs = DB::table('job_postings')->get();
echo "Total jobs (including deleted): " . $allJobs->count() . "\n\n";

foreach ($allJobs as $job) {
    echo "ID: {$job->id}\n";
    echo "Code: {$job->code}\n";
    echo "Title: {$job->title}\n";
    echo "Deleted At: " . ($job->deleted_at ?? 'NULL (not deleted)') . "\n";
    echo "---\n";
}

echo "\n=== Checking via Eloquent (only non-deleted) ===\n\n";

$activeJobs = JobPosting::all();
echo "Total active jobs: " . $activeJobs->count() . "\n\n";

foreach ($activeJobs as $job) {
    echo "ID: {$job->id}\n";
    echo "Code: {$job->code}\n";
    echo "Title: {$job->title}\n";
    echo "---\n";
}

echo "\n=== Checking via Eloquent WITH TRASHED ===\n\n";

$allJobsWithTrashed = JobPosting::withTrashed()->get();
echo "Total jobs (with trashed): " . $allJobsWithTrashed->count() . "\n\n";

foreach ($allJobsWithTrashed as $job) {
    echo "ID: {$job->id}\n";
    echo "Code: {$job->code}\n";
    echo "Title: {$job->title}\n";
    echo "Deleted At: " . ($job->deleted_at ?? 'NULL') . "\n";
    echo "---\n";
}

echo "\n=== Testing Code Generation Query ===\n\n";

$prefix = 'SE';
echo "Prefix: {$prefix}\n\n";

// Old query (without trashed)
$existingCodesOld = JobPosting::where('code', 'like', "{$prefix}-%")
    ->pluck('code')->toArray();
echo "Existing codes (without trashed): " . json_encode($existingCodesOld) . "\n";

// New query (with trashed)
$existingCodesNew = JobPosting::withTrashed()
    ->where('code', 'like', "{$prefix}-%")
    ->pluck('code')->toArray();
echo "Existing codes (with trashed): " . json_encode($existingCodesNew) . "\n";

echo "\n✓ Done\n";
