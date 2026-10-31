<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\Presence;
use App\Models\Samuel;
use App\Models\User;
use Illuminate\Http\Request;

class SamuelController extends Controller
{
    private array $chapitres = [
        1 => 'Histoire du Samuel et prière du servant',
        2 => 'Comportements, qualités et attributs des Samuels',
        3 => 'L\'Assemblée liturgique',
        4 => 'Étude des objets sacrés',
        5 => 'Rôle du polyvalent dans la messe',
        6 => 'Porte-croix',
        7 => 'Rôle du porte-croix dans les différents types de messes',
        8 => 'Importance du Samuel dans l\'Église et surtout la nature',
        9 => 'Pratiques',
    ];

    public function index()
    {
        $data = [];
        foreach ($this->chapitres as $numero => $titre) {
            $data[] = ['numero' => $numero, 'titre' => $titre];
        }
        return response()->json(['chapitres' => $data]);
    }

    public function show($id)
    {
        if (!isset($this->chapitres[$id])) {
            return response()->json(['message' => 'Chapitre introuvable'], 404);
        }

        $chemin = resource_path("chapitres/{$id}.html");
        $contenu = is_file($chemin) ? file_get_contents($chemin) : null;

        return response()->json([
            'numero' => (int) $id,
            'titre' => $this->chapitres[$id],
            'contenu' => $contenu ?: 'Le contenu de ce chapitre sera prochainement disponible.',
        ]);
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'code' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('activation_code', $data['code'])
            ->where('status', 'pending')
            ->first();

        if (!$user && !empty($data['email'])) {
            $user = User::where('email', $data['email'])
                ->where('activation_code', $data['code'])
                ->first();
        }

        if (!$user) {
            return response()->json(['message' => 'Code d\'activation invalide'], 422);
        }

        if (!empty($data['email'])) {
            $dejaUtilise = User::where('email', $data['email'])
                ->where('id', '!=', $user->id)
                ->exists();

            if ($dejaUtilise) {
                return response()->json(['message' => 'Cet email est déjà utilisé'], 422);
            }

            $user->email = $data['email'];
        }

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->status = 'active';
        $user->activation_code = null;
        $user->save();

        Notification::notifierAdmins(
            'Compte activé',
            $user->name . ' a activé son compte via l\'application mobile.',
            'success',
            'mobile',
            ['user_id' => $user->id]
        );

        return response()->json(['message' => 'Compte activé avec succès', 'user' => $user]);
    }

    public function getPresences(Request $request)
    {
        $query = Presence::with('samuel');

        if ($request->filled('date')) {
            $query->whereDate('date_repetition', $request->date);
        }

        return response()->json($query->orderByDesc('date_repetition')->get());
    }

    public function savePresence(Request $request)
    {
        $data = $request->validate([
            'samuel_id' => 'required|exists:samuels,id',
            'date_repetition' => 'required|date',
            'statut' => 'required|in:present,absent,retard,permissionnaire',
            'remarque' => 'nullable|string',
        ]);

        if (\Illuminate\Support\Carbon::parse($data['date_repetition'])->dayOfWeek !== \Illuminate\Support\Carbon::SATURDAY) {
            return response()->json([
                'message' => 'La présence ne peut être enregistrée que le samedi (jour de répétition).',
            ], 422);
        }

        $presence = Presence::where('samuel_id', $data['samuel_id'])
            ->whereDate('date_repetition', $data['date_repetition'])
            ->first();

        if ($presence) {
            $presence->update([
                'statut' => $data['statut'],
                'remarque' => $data['remarque'] ?? null,
                'synchronise' => true,
            ]);
        } else {
            $presence = Presence::create([
                'samuel_id' => $data['samuel_id'],
                'date_repetition' => $data['date_repetition'],
                'statut' => $data['statut'],
                'remarque' => $data['remarque'] ?? null,
                'synchronise' => true,
            ]);
        }

        $samuel = Samuel::find($data['samuel_id']);

        Notification::notifierAdmins(
            'Présence enregistrée (mobile)',
            'Présence de ' . ($samuel->nom_complet ?? 'un Samuel') . ' : ' . $data['statut'] . ' le ' . $data['date_repetition'],
            'info',
            'mobile',
            ['presence_id' => $presence->id]
        );

        Activity::create([
            'user_id' => $request->user()?->id,
            'action' => 'presence.mobile',
            'description' => 'Présence enregistrée depuis le mobile pour ' . ($samuel->nom_complet ?? 'un Samuel'),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Présence enregistrée', 'presence' => $presence]);
    }

    public function getProfil(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
            $data['photo_data'] = User::encodePhoto($request->file('photo'));
        }

        $user->update($data);

        return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
    }

    public function getStatistiques()
    {
        $derniere = Presence::max('date_repetition');
        $derniereDate = $derniere ? \Illuminate\Support\Carbon::parse($derniere)->toDateString() : null;

        return response()->json([
            'total_samuels' => Samuel::count(),
            'total_administrateurs' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'inscriptions_en_attente' => User::where('status', 'pending')->count(),
            'presents' => Presence::whereDate('date_repetition', $derniereDate)->where('statut', 'present')->count(),
            'absents' => Presence::whereDate('date_repetition', $derniereDate)->where('statut', 'absent')->count(),
            'retards' => Presence::whereDate('date_repetition', $derniereDate)->where('statut', 'retard')->count(),
            'permissionnaires' => Presence::whereDate('date_repetition', $derniereDate)->where('statut', 'permissionnaire')->count(),
        ]);
    }

    public function getSamuels()
    {
        return response()->json(
            Samuel::ordonne()->get()
        );
    }

    public function storeSamuel(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
        ]);

        $samuel = Samuel::create([
            'nom' => trim($data['nom']),
            'prenoms' => trim($data['prenoms']),
        ]);

        Notification::notifierAdmins(
            'Nouveau Samuel',
            $samuel->nom_complet . ' a été ajouté depuis l\'application mobile.',
            'success',
            'mobile',
            ['samuel_id' => $samuel->id]
        );

        return response()->json([
            'message' => 'Samuel ajouté avec succès.',
            'samuel' => $samuel,
        ], 201);
    }

    public function updateSamuel(Request $request, $id)
    {
        $samuel = Samuel::findOrFail($id);

        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenoms' => 'sometimes|nullable|string|max:255',
        ]);

        $samuel->update($data);

        return response()->json(['message' => 'Samuel mis à jour', 'samuel' => $samuel]);
    }

    public function destroySamuel($id)
    {
        $samuel = Samuel::findOrFail($id);
        $nom = $samuel->nom_complet;

        $samuel->presences()->delete();
        $samuel->delete();

        Notification::notifierAdmins(
            'Samuel supprimé',
            $nom . ' a été retiré de la liste des Samuels.',
            'warning',
            'mobile',
            []
        );

        Activity::create([
            'user_id' => request()->user()?->id,
            'action' => 'samuel.delete',
            'description' => 'Samuel supprimé depuis le mobile : ' . $nom,
            'ip_address' => request()->ip(),
        ]);

        return response()->json(['message' => 'Samuel supprimé avec succès.']);
    }

    public function getAllSamuels()
    {
        return response()->json(User::all());
    }

    public function updateSamuelRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:user,admin,super_admin']);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        Notification::notifierAdmins(
            'Rôle modifié',
            $user->name . ' a maintenant le rôle : ' . $request->role,
            'warning',
            'mobile',
            ['user_id' => $user->id]
        );

        return response()->json(['message' => 'Rôle mis à jour', 'user' => $user]);
    }
}
