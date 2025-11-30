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
        Schema::create('offer_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('proposed_salary', 15, 2); // Gaji yang diajukan kandidat
            $table->text('candidate_notes')->nullable(); // Alasan negosiasi dari kandidat
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('hr_notes')->nullable(); // Catatan dari HR saat approve/reject
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // HR yang review
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_negotiations');
    }
};
