<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║        CHECKING MASTER DATA FOR JOB POSTING                   ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "📊 CHECKING DIVISIONS:\n";
echo str_repeat("─", 60) . "\n";
$divisions = DB::table('divisions')->where('is_active', true)->get();
if ($divisions->count() > 0) {
    echo "✅ Found {$divisions->count()} active divisions:\n";
    foreach ($divisions as $div) {
        echo "   - ID: {$div->id} | Name: {$div->name}\n";
    }
} else {
    echo "❌ No active divisions found!\n";
    echo "   Please create divisions first in Super Admin → Master Data\n";
}

echo "\n📊 CHECKING POSITIONS:\n";
echo str_repeat("─", 60) . "\n";
$positions = DB::table('positions')->where('is_active', true)->get();
if ($positions->count() > 0) {
    echo "✅ Found {$positions->count()} active positions:\n";
    foreach ($positions as $pos) {
        echo "   - ID: {$pos->id} | Code: {$pos->code} | Name: {$pos->name}\n";
    }
} else {
    echo "❌ No active positions found!\n";
    echo "   Please create positions first in Super Admin → Master Data\n";
}

echo "\n📊 CHECKING LOCATIONS:\n";
echo str_repeat("─", 60) . "\n";
$locations = DB::table('locations')->where('is_active', true)->get();
if ($locations->count() > 0) {
    echo "✅ Found {$locations->count()} active locations:\n";
    foreach ($locations as $loc) {
        echo "   - ID: {$loc->id} | Name: {$loc->name}\n";
    }
} else {
    echo "❌ No active locations found!\n";
    echo "   Please create locations first in Super Admin → Master Data\n";
}

echo "\n" . str_repeat("═", 60) . "\n\n";

if ($divisions->count() > 0 && $positions->count() > 0 && $locations->count() > 0) {
    echo "✅ ALL MASTER DATA READY!\n\n";
    echo "You can now create job postings.\n";
} else {
    echo "⚠️  MISSING MASTER DATA!\n\n";
    echo "Before creating job postings, please:\n";
    echo "1. Login as Super Admin\n";
    echo "2. Go to Master Data menu\n";
    echo "3. Create at least:\n";
    if ($divisions->count() == 0) echo "   - 1 Division\n";
    if ($positions->count() == 0) echo "   - 1 Position (with code field)\n";
    if ($locations->count() == 0) echo "   - 1 Location\n";
}

echo "\n" . str_repeat("═", 60) . "\n\n";

echo "🔍 TESTING JOB CODE GENERATION:\n";
echo str_repeat("─", 60) . "\n";

if ($positions->count() > 0) {
    $testPosition = $positions->first();
    echo "Test Position: {$testPosition->name} (Code: {$testPosition->code})\n";
    
    $prefix = strtoupper(substr($testPosition->code, 0, 3));
    echo "Generated Prefix: {$prefix}\n";
    
    $lastJob = DB::table('job_postings')
        ->where('code', 'like', $prefix . '%')
        ->orderBy('code', 'desc')
        ->first();
    
    if ($lastJob) {
        $lastNumber = (int) substr($lastJob->code, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }
    
    $generatedCode = $prefix . '-' . $newNumber;
    echo "Next Job Code: {$generatedCode}\n";
    echo "✅ Job code generation works!\n";
} else {
    echo "⏭️  Skipped - No positions available\n";
}

echo "\n" . str_repeat("═", 60) . "\n\n";
