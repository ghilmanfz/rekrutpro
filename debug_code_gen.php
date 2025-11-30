<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JobPosting;
use App\Models\Position;

echo "DEBUG: Job Code Generation\n";
echo str_repeat("=", 60) . "\n\n";

$position = Position::find(1);
echo "Position: {$position->name}\n";
echo "Position Code: {$position->code}\n\n";

$prefix = strtoupper($position->code);
echo "Prefix: {$prefix}\n\n";

// Get all existing codes
$existingCodes = JobPosting::where('code', 'like', $prefix . '-%')
    ->pluck('code')
    ->toArray();

echo "Existing codes with prefix '{$prefix}-':\n";
print_r($existingCodes);
echo "\n";

// Extract numbers
$existingNumbers = [];
foreach ($existingCodes as $code) {
    $parts = explode('-', $code);
    echo "Code: {$code} | Parts: " . implode(', ', $parts);
    if (count($parts) >= 2) {
        $number = (int) end($parts);
        $existingNumbers[] = $number;
        echo " | Number: {$number}\n";
    } else {
        echo " | NO NUMBER\n";
    }
}

echo "\nExtracted numbers: ";
print_r($existingNumbers);

if (empty($existingNumbers)) {
    $newNumber = 1;
    echo "\nNo existing numbers, starting from: 1\n";
} else {
    $maxNumber = max($existingNumbers);
    $newNumber = $maxNumber + 1;
    echo "\nMax number: {$maxNumber}\n";
    echo "New number: {$newNumber}\n";
}

$newCode = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
echo "\nGenerated code: {$newCode}\n";

// Check if exists
$exists = JobPosting::where('code', $newCode)->exists();
echo "Exists in DB: " . ($exists ? 'YES ❌' : 'NO ✓') . "\n";
