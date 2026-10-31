<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PremiereConnexionController extends Controller
{
    /**
     * Formulaire de première connexion : le Samuel choisit lui-même
     * son mot de passe à partir du code d'activation reçu par email.
     */
    public function create(): View
    {
        return view('samuel.premiere-connexion');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'max:10'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => "L'adresse email est obligatoire.",
            'code.required' => "Le code d'activation est obligatoire.",
            'password.required' => 'Choisissez un mot de passe.',
            'password.confirmed' => 'Les deux mots de passe ne correspondent pas.',
        ]);

        $user = User::where('email', $data['email'])
            ->where('activation_code', $data['code'])
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['code' => "Email ou code d'activation incorrect."]);
        }

        if ($user->status === 'disabled') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Ce compte est désactivé. Contactez l'administrateur principal."]);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'status' => 'active',
            'activation_code' => null,
        ])->save();

        Notification::notifierAdmins(
            'Compte activé (web)',
            $user->name . ' a défini son mot de passe et activé son compte web.',
            'success',
            'web',
            ['user_id' => $user->id]
        );

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        $accueil = $user->role === 'super_admin'
            ? route('dashboard', absolute: false)
            : route('espace', absolute: false);

        return redirect($accueil)
            ->with('success', 'Bienvenue ' . $user->name . ' ! Votre mot de passe est enregistré.');
    }
}
