<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::create('offer_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('proposed_salary', 15, 2);  
            $table->text('candidate_notes')->nullable();  
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('hr_notes')->nullable();  
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();  
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    


    public function down(): void
    {
        Schema::dropIfExists('offer_negotiations');
    }
};
