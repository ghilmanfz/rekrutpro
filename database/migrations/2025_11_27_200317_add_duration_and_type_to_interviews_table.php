<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->integer('duration')->default(60)->after('scheduled_at');  
            $table->enum('interview_type', ['phone', 'video', 'onsite'])->default('video')->after('duration');
        });
    }

    


    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['duration', 'interview_type']);
        });
    }
};
