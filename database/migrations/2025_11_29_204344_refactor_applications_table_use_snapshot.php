<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
             
            $table->json('candidate_snapshot')->after('candidate_id')->comment('Snapshot data kandidat saat melamar');
            
             
            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'address',
                'birth_date',
                'gender',
                'education',
                'experience'
            ]);
        });
    }

    


    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
             
            $table->string('full_name')->after('candidate_id');
            $table->string('email')->after('full_name');
            $table->string('phone')->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('gender')->nullable()->after('birth_date');
            $table->json('education')->nullable()->after('gender');
            $table->json('experience')->nullable()->after('education');
            
             
            $table->dropColumn('candidate_snapshot');
        });
    }
};
