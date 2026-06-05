<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offer_negotiations', function (Blueprint $table) {
            $table->decimal('counter_offer_salary', 12, 2)->nullable()->after('hr_notes');
        });
    }

    public function down(): void
    {
        Schema::table('offer_negotiations', function (Blueprint $table) {
            $table->dropColumn('counter_offer_salary');
        });
    }
};
