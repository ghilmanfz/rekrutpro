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
            if (!Schema::hasColumn('users', 'registration_step')) {
                $table->tinyInteger('registration_step')->default(1)->after('is_verified');
                // 1 = Basic Info, 2 = Upload CV, 3 = Verify OTP, 4 = Profile Details, 5 = Complete
            }
            if (!Schema::hasColumn('users', 'registration_completed')) {
                $table->boolean('registration_completed')->default(false)->after('registration_step');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'registration_step')) {
                $table->dropColumn('registration_step');
            }
            if (Schema::hasColumn('users', 'registration_completed')) {
                $table->dropColumn('registration_completed');
            }
        });
    }
};
