<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    


    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('offered_by')->constrained('users')->onDelete('cascade');
            
             
            $table->string('position_title');
            $table->decimal('salary', 12, 2);
            $table->string('salary_currency')->default('IDR');
            $table->string('salary_period')->default('monthly');  
            
             
            $table->json('benefits')->nullable();  
            
             
            $table->string('contract_type');  
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
             
            $table->text('terms_and_conditions')->nullable();
            $table->text('internal_notes')->nullable();
            
             
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->date('valid_until')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
        });
    }

    


    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
