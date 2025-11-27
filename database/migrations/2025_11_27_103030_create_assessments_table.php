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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews')->onDelete('cascade');
            $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
            
            // Technical Skills
            $table->integer('technical_score')->nullable(); // 0-100
            $table->text('technical_notes')->nullable();
            
            // Communication
            $table->enum('communication_skill', ['sangat_baik', 'baik', 'cukup', 'kurang'])->nullable();
            
            // Problem Solving
            $table->integer('problem_solving_score')->nullable(); // 0-100
            $table->text('problem_solving_notes')->nullable();
            
            // Team Work Potential
            $table->enum('teamwork_potential', ['tinggi', 'sedang', 'rendah'])->nullable();
            
            // Overall
            $table->decimal('overall_score', 5, 2)->nullable(); // Final score
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('additional_notes')->nullable();
            
            // Recommendation
            $table->enum('recommendation', [
                'sangat_direkomendasikan',
                'direkomendasikan',
                'tidak_direkomendasikan'
            ])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
