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

    public function test_les_samuels_sont_ordonnes_avec_tonou_et_agbossaga_en_premier(): void
    {
        $user = $this->superAdmin();

        $autres = Samuel::create(['nom' => 'ALPHA', 'prenoms' => 'Premier']);
        Samuel::create(['nom' => 'AGBOSSAGA', 'prenoms' => 'Jude', 'ordre' => 2]);
        Samuel::create(['nom' => 'TONOU', 'prenoms' => 'Charbel', 'ordre' => 1]);

        $this->assertSame(
            ['TONOU', 'AGBOSSAGA', 'ALPHA'],
            Samuel::ordonne()->pluck('nom')->all()
        );

        $this->actingAs($user)
            ->get('/nouvelle-seance')
            ->assertOk()
            ->assertSeeInOrder(['TONOU', 'AGBOSSAGA', 'ALPHA']);
    }

    public function test_le_champ_ordre_est_modifiable_depuis_le_web(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'TEST', 'prenoms' => 'Temporaire']);

        $this->actingAs($user)->get('/samuels/' . $samuel->id . '/modifier')->assertOk();

        $this->actingAs($user)
            ->put('/samuels/' . $samuel->id, [
                'nom' => 'TEST',
                'prenoms' => 'Temporaire',
                'ordre' => 3,
            ])
            ->assertRedirect('/samuels');

        $this->assertSame(3, $samuel->fresh()->ordre);
    }

    public function test_la_liste_de_presence_est_telechargeable_en_pdf(): void
    {
        $user = $this->superAdmin();
        $samuel = Samuel::create(['nom' => 'TONOU', 'prenoms' => 'Charbel', 'ordre' => 1]);
        $samedi = now()->previous(Carbon::SATURDAY)->toDateString();

        Presence::create([
            'samuel_id' => $samuel->id,
            'date_repetition' => $samedi,
            'statut' => 'present',
            'synchronise' => true,
        ]);

        $response = $this->actingAs($user)->get('/presences/pdf?date=' . $samedi);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));

        $base = $response->baseResponse;
        $contenu = $base instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse
            ? file_get_contents($base->getFile()->getPathname())
            : $response->getContent();

        $this->assertStringStartsWith('%PDF', $contenu);
    }

    public function test_la_date_par_defaut_est_le_samedi_le_plus_recent(): void
    {
        $this->travelTo(Carbon::parse('2026-10-31 10:00:00')); // un samedi

        $this->assertSame('2026-10-31', \App\Support\DateRepetition::resoudre()->toDateString());
        $this->assertSame('2026-10-31', \App\Support\DateRepetition::resoudre('2026-10-31')->toDateString());
        $this->assertSame('2026-10-24', \App\Support\DateRepetition::resoudre('2026-10-29')->toDateString());
        $this->assertSame('2026-10-31', \App\Support\DateRepetition::resoudre('2026-11-02')->toDateString());

        $this->travelBack();
    }

    public function test_l_api_mobile_renvoie_les_samuels_dans_le_meme_ordre(): void
    {
        $user = $this->superAdmin();
        $user->update(['api_token' => 'jeton-de-test']);

        Samuel::create(['nom' => 'ALPHA', 'prenoms' => 'Premier']);
        Samuel::create(['nom' => 'AGBOSSAGA', 'prenoms' => 'Jude', 'ordre' => 2]);
        Samuel::create(['nom' => 'TONOU', 'prenoms' => 'Charbel', 'ordre' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer jeton-de-test')
            ->getJson('/api/admin/samuels');

        $response->assertOk();
        $this->assertSame(
            ['TONOU', 'AGBOSSAGA', 'ALPHA'],
            array_column($response->json(), 'nom')
        );
    }
}
