<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews')->onDelete('cascade');
            $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
            
             
            $table->integer('technical_score')->nullable();  
            $table->text('technical_notes')->nullable();
            
             
            $table->enum('communication_skill', ['sangat_baik', 'baik', 'cukup', 'kurang'])->nullable();
            
             
            $table->integer('problem_solving_score')->nullable();  
            $table->text('problem_solving_notes')->nullable();
            
             
            $table->enum('teamwork_potential', ['tinggi', 'sedang', 'rendah'])->nullable();
            
             
            $table->decimal('overall_score', 5, 2)->nullable();  
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('additional_notes')->nullable();
            
             
            $table->enum('recommendation', [
                'sangat_direkomendasikan',
                'direkomendasikan',
                'tidak_direkomendasikan'
            ])->nullable();
            
            $table->timestamps();
        });
    }

    


    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
