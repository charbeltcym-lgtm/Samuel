<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device' => 'nullable|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 422);
        }

        if ($user->status === 'pending') {
            return response()->json(['message' => 'Compte en attente de validation'], 403);
        }

        if ($user->status === 'disabled') {
            return response()->json(['message' => 'Compte désactivé'], 403);
        }

        $user->update(['api_token' => hash('sha256', Str::random(60))]);

        $device = $data['device'] ?? null;

        Notification::notifierAdmins(
            'Connexion mobile',
            $user->name . ' s\'est connecté à l\'application mobile' . ($device ? ' (' . $device . ')' : '') . '.',
            'info',
            'mobile',
            ['user_id' => $user->id]
        );

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $user->api_token,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'user',
            'status' => 'pending',
        ]);

        Notification::notifierAdmins(
            'Nouvelle inscription (mobile)',
            $user->name . ' vient de créer un compte depuis l\'application mobile. En attente de validation.',
            'info',
            'mobile',
            ['user_id' => $user->id]
        );

        return response()->json([
            'message' => 'Inscription réussie. Votre compte sera validé par l\'administrateur principal.',
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->update(['api_token' => null]);
        }

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
