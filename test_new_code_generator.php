<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use App\Models\User;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║      TESTING IMPROVED JOB CODE GENERATION                     ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Get HR user
$hr = User::where('email', 'hr@rekrutpro.com')->first();
auth()->login($hr);

echo "Creating job posting with new code generator...\n";
echo str_repeat("─", 60) . "\n\n";

$testData = [
    'position_id' => 1, // Software Engineer
    'division_id' => 1,
    'location_id' => 1,
    'title' => 'Test Job with New Generator',
    'description' => 'Testing improved code generation...',
    'requirements' => 'Test requirements',
    'benefits' => 'Test benefits',
    'vacancies' => 1,
    'employment_type' => 'full_time',
    'level' => 'mid',
    'salary_min' => 8000000,
    'salary_max' => 12000000,
    'application_deadline' => now()->addDays(30)->format('Y-m-d'),
    'expected_start_date' => now()->addDays(45)->format('Y-m-d'),
    'created_by' => $hr->id,
    'status' => 'draft',
];

try {
    // Use the controller's method
    $controller = new App\Http\Controllers\HR\JobPostingController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('generateJobCode');
    $method->setAccessible(true);
    
    $code = $method->invoke($controller, $testData['position_id']);
    
    echo "Generated Code: {$code}\n";
    
    // Check if unique
    $exists = JobPosting::where('code', $code)->exists();
    echo "Code Already Exists: " . ($exists ? 'YES ❌' : 'NO ✓') . "\n\n";
    
    if (!$exists) {
        $testData['code'] = $code;
        $job = JobPosting::create($testData);
        
        echo "✅ SUCCESS! Job created:\n";
        echo "   ID: {$job->id}\n";
        echo "   Code: {$job->code}\n";
        echo "   Title: {$job->title}\n\n";
        
        echo "Cleanup...\n";
        $job->delete();
        echo "✓ Test job deleted\n\n";
        
        echo "═══════════════════════════════════════════════════════════\n";
        echo "🎉 NEW CODE GENERATOR WORKS!\n";
        echo "═══════════════════════════════════════════════════════════\n\n";
        echo "✅ The form should work now without duplicate code errors.\n";
    } else {
        echo "❌ ERROR: Generated code already exists!\n";
        echo "   This should not happen with the new generator.\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
