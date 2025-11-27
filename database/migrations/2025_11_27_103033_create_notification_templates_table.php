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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('type'); // email, whatsapp, sms
            $table->string('event'); // application_submitted, interview_scheduled, etc
            
            $table->string('subject')->nullable(); // For email
            $table->text('body');
            
            // Available placeholders: {{name}}, {{position}}, {{date}}, etc
            $table->json('available_placeholders')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
