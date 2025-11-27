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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Auto generate: SE001, PM002, etc
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            
            $table->integer('quota')->default(1);
            $table->string('employment_type')->default('full_time'); // full_time, part_time, contract, internship
            $table->string('experience_level')->default('mid'); // junior, mid, senior
            
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_currency')->default('IDR');
            
            $table->date('published_at')->nullable();
            $table->date('closed_at')->nullable();
            $table->enum('status', ['draft', 'active', 'closed', 'archived'])->default('draft');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
