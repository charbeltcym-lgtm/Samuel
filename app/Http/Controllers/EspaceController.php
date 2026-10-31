<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Samuel;
use App\Models\User;
use App\Services\PresenceService;
use App\Support\Chapitres;
use App\Support\DateRepetition;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EspaceController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('samuel.espace.index', [
            'user' => $user,
        ]);
    }

    public function samuels(): View
    {
        $samuels = Samuel::ordonne()->get();

        return view('samuel.espace.samuels', compact('samuels'));
    }

    public function createSamuel(): View
    {
        return view('samuel.espace.ajouter-samuel');
    }

    public function storeSamuel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
        ]);

        Samuel::create([
            'nom' => trim($data['nom']),
            'prenoms' => trim($data['prenoms']),
        ]);

        return redirect()->route('espace.samuels')->with('success', 'Samuel ajouté avec succès.');
    }

    public function editSamuel(int $id): View
    {
        $samuel = Samuel::findOrFail($id);

        return view('samuel.espace.modifier-samuel', compact('samuel'));
    }

    public function updateSamuel(Request $request, int $id): RedirectResponse
    {
        $samuel = Samuel::findOrFail($id);

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
        ]);

        $samuel->update([
            'nom' => trim($data['nom']),
            'prenoms' => trim($data['prenoms']),
        ]);

        return redirect()->route('espace.samuels')->with('success', 'Samuel modifié avec succès.');
    }

    public function destroySamuel(int $id): RedirectResponse
    {
        $samuel = Samuel::findOrFail($id);
        $samuel->presences()->delete();
        $samuel->delete();

        return back()->with('success', 'Samuel supprimé avec succès.');
    }

    public function presence(Request $request): View
    {
        $date = DateRepetition::resoudre($request->query('date'));
        $dateIso = $date->toDateString();

        $samuels = Samuel::ordonne()->get();
        $presences = Presence::whereDate('date_repetition', $dateIso)
            ->get()
            ->keyBy('samuel_id');

        return view('samuel.espace.presence', compact('date', 'dateIso', 'samuels', 'presences'));
    }

    public function storePresence(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date_repetition' => 'required|date',
            'statuts' => 'nullable|array',
            'statuts.*' => 'nullable|in:present,absent,retard,permissionnaire',
        ], [
            'date_repetition.required' => 'Veuillez choisir une date.',
        ]);

        $carbon = Carbon::parse($data['date_repetition']);

        if ($carbon->dayOfWeek !== Carbon::SATURDAY) {
            return back()
                ->withErrors(['date_repetition' => 'La présence ne peut être enregistrée que le samedi.'])
                ->withInput();
        }

        $statuts = $data['statuts'] ?? [];
        $enregistres = PresenceService::enregistrer($carbon, $statuts);

        return redirect()->route('espace.presence', ['date' => $carbon->toDateString()])
            ->with('success', $enregistres . ' présence(s) enregistrée(s).');
    }

    public function chapitre(int $numero): View
    {
        $titres = Chapitres::tous();
        abort_unless(isset($titres[$numero]), 404);

        return view('samuel.espace.chapitre', [
            'numero' => $numero,
            'titre' => $titres[$numero],
            'contenu' => Chapitres::contenu($numero),
        ]);
    }
}
