<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date_repetition');
            $table->enum('statut', ['present', 'absent', 'retard', 'permissionnaire'])->default('absent');
            $table->string('remarque')->nullable();
            $table->boolean('synchronise')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'date_repetition']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
