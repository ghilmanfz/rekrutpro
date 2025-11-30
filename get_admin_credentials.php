<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SUPER ADMIN CREDENTIALS ===\n\n";

$admin = DB::table('users')->where('role_id', 1)->first();

if ($admin) {
    echo "✓ Super Admin found\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ID       : {$admin->id}\n";
    echo "Name     : {$admin->name}\n";
    echo "Email    : {$admin->email}\n";
    echo "Password : password (default from seeder)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "LOGIN URL: http://127.0.0.1:8000/login\n";
    echo "DASHBOARD: http://127.0.0.1:8000/superadmin/dashboard\n";
} else {
    echo "❌ Super Admin not found!\n";
}

echo "\n=== ALL ROLES ===\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    $count = DB::table('users')->where('role_id', $role->id)->count();
    echo "- {$role->display_name} (ID: {$role->id}): {$count} users\n";
}

echo "\n";
