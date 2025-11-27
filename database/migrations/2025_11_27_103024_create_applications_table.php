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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // AP-2024-001
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            
            // Candidate Profile Data
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            
            // Education
            $table->json('education')->nullable(); // [{degree, institution, major, year}]
            
            // Experience
            $table->json('experience')->nullable(); // [{position, company, duration, description}]
            
            // Documents
            $table->string('cv_file')->nullable();
            $table->string('cover_letter')->nullable();
            $table->string('portfolio_file')->nullable();
            $table->json('other_documents')->nullable();
            
            // Application Status
            $table->enum('status', [
                'submitted',
                'screening_passed', 
                'rejected_admin',
                'interview_scheduled',
                'interview_passed',
                'rejected_interview',
                'offered',
                'hired',
                'rejected_offer',
                'archived'
            ])->default('submitted');
            
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
