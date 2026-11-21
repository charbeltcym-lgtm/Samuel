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
        Schema::table('presence_sessions', function (Blueprint $table) {
            $table->longText('signature')->nullable()->after('signed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presence_sessions', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
    }
};
