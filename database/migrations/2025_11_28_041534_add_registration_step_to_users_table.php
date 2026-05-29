<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'registration_step')) {
                $table->tinyInteger('registration_step')->default(1)->after('is_verified');
                 
            }
            if (!Schema::hasColumn('users', 'registration_completed')) {
                $table->boolean('registration_completed')->default(false)->after('registration_step');
            }
        });
    }

    


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
