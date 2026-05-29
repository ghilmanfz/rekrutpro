<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    


    public function up(): void
    {
         
        if (!Schema::hasColumn('notification_templates', 'channel')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->string('channel')->nullable()->after('type');
            });
        }

         
        DB::table('notification_templates')
            ->whereNull('channel')
            ->whereNotNull('type')
            ->update(['channel' => DB::raw('type')]);

         
         
    }

    


    public function down(): void
    {
        if (Schema::hasColumn('notification_templates', 'channel')) {
            Schema::table('notification_templates', function (Blueprint $table) {
                $table->dropColumn('channel');
            });
        }
    }
};
