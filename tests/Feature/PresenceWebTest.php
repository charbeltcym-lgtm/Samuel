<?php

namespace Tests\Feature;

use App\Models\Presence;
use App\Models\Samuel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PresenceWebTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Super Admin',
            'email' => 'super-admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);
    }

    public function test_un_super_admin_peut_faire_la_presence_du_samedi(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'TEST', 'prenoms' => 'Temporaire']);
        $samedi = now()->next(Carbon::SATURDAY)->toDateString();

        $this->actingAs($user)->get('/nouvelle-seance')->assertOk();

        $this->actingAs($user)
            ->post('/nouvelle-seance', [
                'date_repetition' => $samedi,
                'statuts' => [$samuel->id => 'retard'],
            ])
            ->assertRedirect('/presences');

        $this->assertDatabaseHas('presences', [
            'samuel_id' => $samuel->id,
            'statut' => 'retard',
        ]);
    }

    public function test_reenregistrer_la_meme_date_met_a_jour_sans_doublon(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'TEST', 'prenoms' => 'Temporaire']);
        $samedi = now()->next(Carbon::SATURDAY)->toDateString();

        foreach (['retard', 'present'] as $statut) {
            $this->actingAs($user)
                ->post('/nouvelle-seance', [
                    'date_repetition' => $samedi,
                    'statuts' => [$samuel->id => $statut],
                ])
                ->assertRedirect('/presences');
        }

        $this->assertSame(1, Presence::where('samuel_id', $samuel->id)->count());
        $this->assertSame('present', Presence::where('samuel_id', $samuel->id)->first()->statut);
    }

    public function test_la_presence_est_refusee_un_jour_autre_que_le_samedi(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'TEST', 'prenoms' => 'Temporaire']);

        $this->actingAs($user)
            ->post('/nouvelle-seance', [
                'date_repetition' => now()->next(Carbon::MONDAY)->toDateString(),
                'statuts' => [$samuel->id => 'present'],
            ])
            ->assertSessionHasErrors('date_repetition');

        $this->assertSame(0, Presence::count());
    }

    public function test_un_super_admin_peut_modifier_le_nom_d_un_samuel(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'ANCIEN', 'prenoms' => 'Prenom']);

        Presence::create([
            'samuel_id' => $samuel->id,
            'date_repetition' => now()->next(Carbon::SATURDAY)->toDateString(),
            'statut' => 'present',
            'synchronise' => true,
        ]);

        $this->actingAs($user)->get('/samuels/' . $samuel->id . '/modifier')->assertOk();

        $this->actingAs($user)
            ->put('/samuels/' . $samuel->id, [
                'nom' => 'NOUVEAU',
                'prenoms' => 'Nouveau Prenom',
            ])
            ->assertRedirect('/samuels');

        $this->assertDatabaseHas('samuels', [
            'id' => $samuel->id,
            'nom' => 'NOUVEAU',
            'prenoms' => 'Nouveau Prenom',
        ]);

        // Les présences du Samuel sont conservées après le renommage.
        $this->assertSame(1, Presence::where('samuel_id', $samuel->id)->count());

        // L'historique groupé par samedi s'affiche.
        $this->actingAs($user)->get('/presences')->assertOk()->assertSee('Présents');
    }

    public function test_un_admin_simple_ne_peut_pas_acceder_aux_pages_web(): void
    {
        $admin = User::create([
            'name' => 'Admin simple',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->get('/nouvelle-seance')->assertRedirect('/login');
        $this->actingAs($admin)->get('/samuels')->assertRedirect('/login');
        $this->assertGuest();
    }
}
