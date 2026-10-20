<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCodeMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            return response()->json([
                'message' => 'Compte en attente d\'activation. Utilisez « Première connexion » avec le code reçu par email.',
                'requires_activation' => true,
            ], 403);
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
            'message' => 'Inscription réussie. Votre compte sera validé par l\'administrateur principal : vous recevrez votre code d\'activation par email.',
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

    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Aucun compte n\'est associé à cet email.'], 422);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(30),
        ]);

        $envoye = false;
        try {
            Mail::to($user->email)->send(new ResetPasswordCodeMail($user->name, $code));
            $envoye = true;
        } catch (\Throwable $e) {
            $envoye = false;
        }

        return response()->json([
            'message' => $envoye
                ? 'Un code de réinitialisation vous a été envoyé par email.'
                : 'Code généré, mais l\'email n\'a pas pu être envoyé. Contactez l\'administrateur.',
            'email_sent' => $envoye,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->reset_code || $user->reset_code !== $data['code']) {
            return response()->json(['message' => 'Code de réinitialisation invalide.'], 422);
        }

        if ($user->reset_code_expires_at && now()->greaterThan($user->reset_code_expires_at)) {
            return response()->json(['message' => 'Ce code a expiré. Veuillez refaire la demande.'], 422);
        }

        $user->update([
            'password' => $data['password'],
            'reset_code' => null,
            'reset_code_expires_at' => null,
            'api_token' => null,
        ]);

        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.',
        ]);
    }
}
