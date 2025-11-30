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
            // Tambah field candidate_snapshot untuk menyimpan data kandidat saat apply
            $table->json('candidate_snapshot')->after('candidate_id')->comment('Snapshot data kandidat saat melamar');
            
            // Hapus field duplikat yang sudah ada di users table
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Kembalikan field yang dihapus
            $table->string('full_name')->after('candidate_id');
            $table->string('email')->after('full_name');
            $table->string('phone')->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('gender')->nullable()->after('birth_date');
            $table->json('education')->nullable()->after('gender');
            $table->json('experience')->nullable()->after('education');
            
            // Hapus field snapshot
            $table->dropColumn('candidate_snapshot');
        });
    }
};
