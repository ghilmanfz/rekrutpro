<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->onDelete('set null');
            $table->foreignId('division_id')->nullable()->after('role_id')->constrained('divisions')->onDelete('set null');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('profile_photo');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('otp_code')->nullable()->after('last_login_at');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->boolean('is_verified')->default(false)->after('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['division_id']);
            $table->dropColumn([
                'role_id', 'division_id', 'phone', 'address', 
                'profile_photo', 'is_active', 'last_login_at',
                'otp_code', 'otp_expires_at', 'is_verified'
            ]);
        });
    }
};
