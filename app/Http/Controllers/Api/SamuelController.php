<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return response()->json([
            'numero' => (int) $id,
            'titre' => $this->chapitres[$id],
            'contenu' => 'Le contenu de ce chapitre sera prochainement disponible.',
        ]);
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->activation_code || !hash_equals($user->activation_code, $data['code'])) {
            return response()->json(['message' => 'Email ou code d\'activation invalide'], 422);
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
        $query = Presence::with('user');

        if ($request->filled('date')) {
            $query->whereDate('date_repetition', $request->date);
        }

        return response()->json($query->orderByDesc('date_repetition')->get());
    }

    public function savePresence(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_repetition' => 'required|date',
            'statut' => 'required|in:present,absent,retard,permissionnaire',
            'remarque' => 'nullable|string',
        ]);

        if (\Illuminate\Support\Carbon::parse($data['date_repetition'])->dayOfWeek !== \Illuminate\Support\Carbon::SATURDAY) {
            return response()->json([
                'message' => 'La présence ne peut être enregistrée que le samedi (jour de répétition).',
            ], 422);
        }

        $presence = Presence::where('user_id', $data['user_id'])
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
                'user_id' => $data['user_id'],
                'date_repetition' => $data['date_repetition'],
                'statut' => $data['statut'],
                'remarque' => $data['remarque'] ?? null,
                'synchronise' => true,
            ]);
        }

        $samuel = User::find($data['user_id']);

        Notification::notifierAdmins(
            'Présence enregistrée (mobile)',
            'Présence de ' . ($samuel->name ?? 'un Samuel') . ' : ' . $data['statut'] . ' le ' . $data['date_repetition'],
            'info',
            'mobile',
            ['presence_id' => $presence->id]
        );

        Activity::create([
            'user_id' => $request->user()?->id,
            'action' => 'presence.mobile',
            'description' => 'Présence enregistrée depuis le mobile pour ' . ($samuel->name ?? 'un Samuel'),
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
        }

        $user->update($data);

        return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
    }

    public function getStatistiques()
    {
        $derniere = Presence::max('date_repetition');
        $derniereDate = $derniere ? \Illuminate\Support\Carbon::parse($derniere)->toDateString() : null;

        return response()->json([
            'total_samuels' => User::where('role', 'user')->count(),
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
        return response()->json(User::where('role', 'user')->get());
    }

    public function storeSamuel(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $code = strtoupper(Str::random(8));

        $samuel = User::create([
            'name' => trim($data['nom'] . ' ' . $data['prenoms']),
            'email' => $data['email'],
            'password' => Str::random(16),
            'role' => 'user',
            'status' => 'pending',
            'activation_code' => $code,
        ]);

        Notification::notifierAdmins(
            'Nouveau Samuel',
            $samuel->name . ' a été ajouté depuis l\'application mobile.',
            'success',
            'mobile',
            ['user_id' => $samuel->id]
        );

        return response()->json([
            'message' => 'Samuel ajouté. Transmettez-lui le code d\'activation.',
            'samuel' => $samuel,
            'code' => $code,
        ], 201);
    }

    public function updateSamuel(Request $request, $id)
    {
        $samuel = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'status' => 'sometimes|in:pending,active,disabled',
        ]);

        $samuel->update($data);

        return response()->json(['message' => 'Samuel mis à jour', 'samuel' => $samuel]);
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
