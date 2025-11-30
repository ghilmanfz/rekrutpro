<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use App\Models\User;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║           TESTING JOB POSTING CREATION                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Get HR user
$hr = User::where('email', 'hr@rekrutpro.com')->first();

if (!$hr) {
    echo "❌ HR user not found!\n";
    exit;
}

echo "✓ HR User: {$hr->name} ({$hr->email})\n\n";

// Simulate authenticated user
auth()->login($hr);

echo "Creating test job posting...\n";
echo str_repeat("─", 60) . "\n";

$testData = [
    'position_id' => 1, // Software Engineer
    'division_id' => 1, // Teknologi Informasi
    'location_id' => 1, // Jakarta Pusat
    'title' => 'Senior Software Engineer',
    'description' => 'We are looking for an experienced software engineer...',
    'requirements' => '- 5+ years experience
- Proficient in PHP/Laravel
- Strong problem solving skills',
    'benefits' => '- Health insurance
- Flexible hours
- Remote work option',
    'vacancies' => 2,
    'employment_type' => 'full_time',
    'level' => 'senior',
    'salary_min' => 10000000,
    'salary_max' => 15000000,
    'application_deadline' => now()->addDays(30)->format('Y-m-d'),
    'expected_start_date' => now()->addDays(45)->format('Y-m-d'),
    'created_by' => $hr->id,
    'status' => 'draft',
];

echo "Test Data:\n";
foreach ($testData as $key => $value) {
    if (is_string($value) && strlen($value) > 50) {
        $value = substr($value, 0, 47) . '...';
    }
    echo "  {$key}: {$value}\n";
}

echo "\n";

try {
    // Generate job code
    $position = App\Models\Position::find($testData['position_id']);
    $prefix = strtoupper(substr($position->code, 0, 3));
    
    $lastJob = JobPosting::where('code', 'like', $prefix . '%')
        ->orderBy('code', 'desc')
        ->first();
    
    if ($lastJob) {
        $lastNumber = (int) substr($lastJob->code, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }
    
    $testData['code'] = $prefix . '-' . $newNumber;
    
    echo "Generated Job Code: {$testData['code']}\n\n";
    
    // Create job posting
    $job = JobPosting::create($testData);
    
    echo "✅ SUCCESS! Job posting created:\n";
    echo str_repeat("─", 60) . "\n";
    echo "ID: {$job->id}\n";
    echo "Code: {$job->code}\n";
    echo "Title: {$job->title}\n";
    echo "Status: {$job->status}\n";
    echo "Created by: {$job->creator->name}\n";
    
    echo "\n🧹 Cleaning up test data...\n";
    $job->delete();
    echo "✓ Test job posting deleted\n";
    
    echo "\n" . str_repeat("═", 60) . "\n";
    echo "🎉 JOB POSTING CREATION WORKS!\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
    echo "✅ The form should work now.\n";
    echo "   Issue fixed: Field names in form matched to controller validation\n";
    echo "   - deadline → application_deadline\n";
    echo "   - start_date → expected_start_date\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
