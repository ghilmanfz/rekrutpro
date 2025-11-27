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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('application_code')->nullable()->after('code');
            $table->timestamp('screening_passed_at')->nullable()->after('reviewed_at');
            $table->timestamp('interview_scheduled_at')->nullable()->after('screening_passed_at');
            $table->timestamp('interview_passed_at')->nullable()->after('interview_scheduled_at');
            $table->timestamp('offered_at')->nullable()->after('interview_passed_at');
            $table->timestamp('hired_at')->nullable()->after('offered_at');
            $table->text('status_notes')->nullable()->after('hired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'application_code',
                'screening_passed_at',
                'interview_scheduled_at',
                'interview_passed_at',
                'offered_at',
                'hired_at',
                'status_notes'
            ]);
        });
    }
};
