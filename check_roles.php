<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING ROLES ===\n\n";

$roles = DB::table('roles')->get();

foreach ($roles as $role) {
    echo "ID: {$role->id} - Name: {$role->name}\n";
}

echo "\n=== CHECKING SUPER ADMIN USER ===\n\n";

$admin = DB::table('users')
    ->where('email', 'admin@rekrutpro.com')
    ->first();

if ($admin) {
    echo "Email: {$admin->email}\n";
    echo "Name: {$admin->name}\n";
    echo "Role ID: {$admin->role_id}\n";
    
    $role = DB::table('roles')->where('id', $admin->role_id)->first();
    echo "Role Name: " . ($role ? $role->name : 'NOT FOUND') . "\n";
    echo "Registration Completed: " . ($admin->registration_completed ? 'YES' : 'NO') . "\n";
    echo "Is Active: " . ($admin->is_active ? 'YES' : 'NO') . "\n";
} else {
    echo "Super Admin not found!\n";
}
