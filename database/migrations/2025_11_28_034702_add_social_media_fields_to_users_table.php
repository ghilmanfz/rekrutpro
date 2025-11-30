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
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'education')) {
                $table->text('education')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'experience')) {
                $table->text('experience')->nullable()->after('education');
            }
            if (!Schema::hasColumn('users', 'skills')) {
                $table->text('skills')->nullable()->after('experience');
            }
            if (!Schema::hasColumn('users', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('skills');
            }
            if (!Schema::hasColumn('users', 'github_url')) {
                $table->string('github_url')->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('users', 'portfolio_url')) {
                $table->string('portfolio_url')->nullable()->after('github_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['date_of_birth', 'education', 'experience', 'skills', 'linkedin_url', 'github_url', 'portfolio_url'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
