<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Copier les Samuels existants (utilisateurs role=user) vers la table samuels
        $map = [];
        if (Schema::hasTable('users') && Schema::hasTable('samuels')) {
            $users = DB::table('users')->where('role', 'user')->get();
            foreach ($users as $u) {
                $parts = preg_split('/\s+/', trim((string) $u->name), 2);
                $nom = $parts[0] ?? '';
                $prenoms = $parts[1] ?? '';
                $map[$u->id] = DB::table('samuels')->insertGetId([
                    'nom' => $nom !== '' ? $nom : 'Samuel',
                    'prenoms' => $prenoms,
                    'actif' => true,
                    'created_at' => $u->created_at ?? now(),
                    'updated_at' => $u->updated_at ?? now(),
                ]);
            }
        }

        // 2) Sauvegarder les présences existantes
        $anciennes = [];
        if (Schema::hasTable('presences')) {
            $anciennes = DB::table('presences')->get();
        }

        // 3) Recréer la table presences liée à samuels
        Schema::dropIfExists('presences');
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('samuel_id')->constrained('samuels')->cascadeOnDelete();
            $table->date('date_repetition');
            $table->enum('statut', ['present', 'absent', 'retard', 'permissionnaire'])->default('absent');
            $table->string('remarque')->nullable();
            $table->boolean('synchronise')->default(false);
            $table->timestamps();

            $table->unique(['samuel_id', 'date_repetition']);
        });

        // 4) Réinsérer les présences liées à un Samuel existant
        foreach ($anciennes as $p) {
            if (isset($map[$p->user_id])) {
                DB::table('presences')->insert([
                    'samuel_id' => $map[$p->user_id],
                    'date_repetition' => $p->date_repetition,
                    'statut' => $p->statut,
                    'remarque' => $p->remarque,
                    'synchronise' => $p->synchronise,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
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
};
