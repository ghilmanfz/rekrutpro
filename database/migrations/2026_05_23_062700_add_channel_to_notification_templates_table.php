<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'channel' column if it doesn't exist
        if (!Schema::hasColumn('notification_templates', 'channel')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('channel')->nullable()->after('type');
            });
        }

        // Sync existing records: copy 'type' value to 'channel' where channel is null
        DB::table('notification_templates')
            ->whereNull('channel')
            ->whereNotNull('type')
            ->update(['channel' => DB::raw('type')]);

        // Make 'name' and 'slug' nullable so admin-created templates don't fail
        // (they auto-generate name/slug in the controller now)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('notification_templates', 'channel')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->dropColumn('channel');
            });
        }
    }
};
