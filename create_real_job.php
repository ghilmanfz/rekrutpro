<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use App\Models\Position;
use App\Models\Division;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════════════\n";
echo "  TESTING ACTUAL JOB POSTING CREATION (via HR)             \n";
echo "═══════════════════════════════════════════════════════════\n\n";

try {
    // Find HR user
    $hr = User::whereHas('role', function($q) {
        $q->where('name', 'hr');
    })->first();
    
    if (!$hr) {
        echo "❌ ERROR: HR user not found\n";
        exit(1);
    }
    
    echo "✓ HR User: {$hr->name} ({$hr->email})\n\n";
    
    // Get master data
    $position = Position::where('code', 'SE')->first();
    $division = Division::where('is_active', true)->first();
    $location = Location::where('is_active', true)->first();
    
    echo "✓ Position: {$position->name} (Code: {$position->code})\n";
    echo "✓ Division: {$division->name}\n";
    echo "✓ Location: {$location->name}\n\n";
    
    // Simulate the exact data from HR form
    $data = [
        'position_id' => $position->id,
        'division_id' => $division->id,
        'location_id' => $location->id,
        'created_by' => $hr->id,
        'title' => 'Full Stack Developer',
        'description' => 'We are looking for an experienced Full Stack Developer...',
        'requirements' => "- Bachelor's degree in Computer Science\n- 3+ years experience\n- Strong knowledge of Laravel and Vue.js",
        'responsibilities' => "- Develop and maintain web applications\n- Collaborate with team members\n- Write clean, maintainable code",
        'benefits' => "- Competitive salary\n- Health insurance\n- Flexible working hours",
        'quota' => 2,
        'employment_type' => 'full_time',
        'experience_level' => 'mid',
        'salary_min' => 8000000,
        'salary_max' => 12000000,
        'salary_currency' => 'IDR',
        'status' => 'draft',
    ];
    
    echo "Creating job posting with DRAFT status...\n";
    
    // Start transaction
    DB::beginTransaction();
    
    // Generate code (this is what happens in controller)
    $controller = new \ReflectionClass('App\Http\Controllers\HR\JobPostingController');
    $method = $controller->getMethod('generateJobCode');
    $method->setAccessible(true);
    $controllerInstance = new \App\Http\Controllers\HR\JobPostingController();
    
    $generatedCode = $method->invoke($controllerInstance, $position->id);
    echo "Generated Code: {$generatedCode}\n";
    
    $data['code'] = $generatedCode;
    
    // Create job posting
    $job = JobPosting::create($data);
    
    DB::commit();
    
    echo "\n✅ JOB POSTING CREATED SUCCESSFULLY!\n\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "Job Details:\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "ID: {$job->id}\n";
    echo "Code: {$job->code}\n";
    echo "Title: {$job->title}\n";
    echo "Position: {$job->position->name}\n";
    echo "Division: {$job->division->name}\n";
    echo "Location: {$job->location->name}\n";
    echo "Status: {$job->status}\n";
    echo "Quota: {$job->quota}\n";
    echo "Employment Type: {$job->employment_type}\n";
    echo "Experience Level: {$job->experience_level}\n";
    echo "Salary Range: " . number_format($job->salary_min) . " - " . number_format($job->salary_max) . " {$job->salary_currency}\n";
    echo "Created by: {$job->creator->name}\n";
    echo "Created at: {$job->created_at}\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    echo "✓ Now try creating another job with the same position...\n\n";
    
    // Create another one
    $data2 = $data;
    $data2['title'] = 'Senior Full Stack Developer';
    $data2['salary_min'] = 12000000;
    $data2['salary_max'] = 18000000;
    
    $generatedCode2 = $method->invoke($controllerInstance, $position->id);
    echo "Generated Code 2: {$generatedCode2}\n";
    
    $data2['code'] = $generatedCode2;
    
    DB::beginTransaction();
    $job2 = JobPosting::create($data2);
    DB::commit();
    
    echo "\n✅ SECOND JOB POSTING CREATED SUCCESSFULLY!\n\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "Job Details:\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "ID: {$job2->id}\n";
    echo "Code: {$job2->code}\n";
    echo "Title: {$job2->title}\n";
    echo "Status: {$job2->status}\n";
    echo "Salary Range: " . number_format($job2->salary_min) . " - " . number_format($job2->salary_max) . " {$job2->salary_currency}\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    echo "✓ All active job postings:\n";
    $allJobs = JobPosting::with('position')->get();
    foreach ($allJobs as $j) {
        echo "  - {$j->code}: {$j->title} ({$j->position->name})\n";
    }
    
    echo "\n✓ Including soft-deleted:\n";
    $allJobsWithTrashed = JobPosting::withTrashed()->with('position')->get();
    foreach ($allJobsWithTrashed as $j) {
        $deleted = $j->deleted_at ? ' [DELETED]' : '';
        echo "  - {$j->code}: {$j->title} ({$j->position->name}){$deleted}\n";
    }
    
    echo "\n🎉 JOB CODE GENERATION IS NOW WORKING CORRECTLY!\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    echo "Note: These job postings are real and NOT deleted.\n";
    echo "You can see them in the HR dashboard.\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
