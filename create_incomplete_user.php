<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREATE INCOMPLETE REGISTRATION USER ===\n\n";

// Get candidate role
$candidateRole = DB::table('roles')->where('name', 'candidate')->first();

// Create incomplete user (stopped at step 2)
$email = 'incomplete' . time() . '@test.com';
$password = bcrypt('password123');

DB::table('users')->insert([
    'name' => 'Test Incomplete User',
    'email' => $email,
    'password' => $password,
    'role_id' => $candidateRole->id,
    'registration_step' => 2,
    'registration_completed' => 0,
    'is_active' => 0,
    'is_verified' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "✓ Incomplete user created\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Email    : {$email}\n";
echo "Password : password123\n";
echo "Step     : 2 (stopped after step 1)\n";
echo "Status   : Incomplete\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "TESTING INSTRUCTIONS:\n";
echo "1. Logout dari dashboard jika masih login\n";
echo "2. Coba login dengan:\n";
echo "   Email: {$email}\n";
echo "   Password: password123\n";
echo "3. EXPECTED: Login ditolak dengan error message\n";
echo "   \"Akun Anda belum menyelesaikan proses registrasi\"\n\n";

echo "=== USER CREATED ===\n";
