<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samuels', function (Blueprint $table) {
            $table->integer('ordre')->default(100);
        });

        // TONOU Charbel en premier, puis AGBOSSAGA Jude, puis les autres.
        DB::table('samuels')
            ->whereRaw('lower(nom) = ?', ['tonou'])
            ->whereRaw('lower(prenoms) like ?', ['charbel%'])
            ->update(['ordre' => 1]);

        DB::table('samuels')
            ->whereRaw('lower(nom) = ?', ['agbossaga'])
            ->whereRaw('lower(prenoms) like ?', ['jude%'])
            ->update(['ordre' => 2]);
    }

    public function down(): void
    {
        Schema::table('samuels', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
    }
};
