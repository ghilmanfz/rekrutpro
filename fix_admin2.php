<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING admin2@rekrutpro.com ===\n\n";

$admin2 = DB::table('users')
    ->where('email', 'admin2@rekrutpro.com')
    ->first();

if ($admin2) {
    echo "✓ User found!\n\n";
    echo "Email: {$admin2->email}\n";
    echo "Name: {$admin2->name}\n";
    echo "Role ID: {$admin2->role_id}\n";
    
    $role = DB::table('roles')->where('id', $admin2->role_id)->first();
    echo "Role Name: " . ($role ? $role->name : 'NOT FOUND') . "\n";
    echo "Registration Completed: " . ($admin2->registration_completed ? 'YES' : 'NO') . " ❌\n";
    echo "Is Verified: " . ($admin2->is_verified ? 'YES' : 'NO') . "\n";
    echo "Is Active: " . ($admin2->is_active ? 'YES' : 'NO') . "\n";
    
    echo "\n🔍 PROBLEM DETECTED:\n";
    if (!$admin2->registration_completed) {
        echo "   - registration_completed = FALSE (should be TRUE for internal users)\n";
    }
    
    echo "\n✅ FIXING NOW...\n\n";
    
    // Auto-fix ALL internal users (super_admin, hr, interviewer)
    $updated = DB::table('users')
        ->whereIn('role_id', [1, 2, 3]) // super_admin, hr, interviewer
        ->update([
            'registration_completed' => true,
            'is_verified' => true,
            'is_active' => true,
        ]);
    
    echo "Updated {$updated} internal user(s)\n\n";
    
    // Verify fix
    $admin2Fixed = DB::table('users')
        ->where('email', 'admin2@rekrutpro.com')
        ->first();
    
    echo "=== VERIFICATION AFTER FIX ===\n\n";
    echo "Email: {$admin2Fixed->email}\n";
    echo "Registration Completed: " . ($admin2Fixed->registration_completed ? 'YES ✓' : 'NO') . "\n";
    echo "Is Verified: " . ($admin2Fixed->is_verified ? 'YES ✓' : 'NO') . "\n";
    echo "Is Active: " . ($admin2Fixed->is_active ? 'YES ✓' : 'NO') . "\n";
    
    echo "\n🎉 FIXED! You can now login with:\n";
    echo "   Email: admin2@rekrutpro.com\n";
    echo "   Password: password\n";
    
} else {
    echo "❌ User NOT FOUND in database!\n\n";
    echo "Please add the user first using:\n";
    echo "   php artisan tinker\n";
    echo "   >>> User::create([\n";
    echo "   ...   'name' => 'Admin 2',\n";
    echo "   ...   'email' => 'admin2@rekrutpro.com',\n";
    echo "   ...   'password' => Hash::make('password'),\n";
    echo "   ...   'role_id' => 1,\n";
    echo "   ...   'registration_completed' => true,\n";
    echo "   ...   'is_verified' => true,\n";
    echo "   ...   'is_active' => true,\n";
    echo "   ... ]);\n";
}
