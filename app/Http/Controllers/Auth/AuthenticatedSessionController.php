<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        $accesRefuse = null;

        if ($user->status === 'pending') {
            $accesRefuse = "Votre compte n'est pas encore activé. Utilisez « Première connexion » avec le code reçu par email.";
        } elseif ($user->status === 'disabled') {
            $accesRefuse = "Votre compte est désactivé. Contactez l'administrateur principal.";
        } elseif (! in_array($user->role, ['admin', 'super_admin'], true)) {
            $accesRefuse = "L'espace web est réservé aux administrateurs. Utilisez l'application mobile.";
        }

        if ($accesRefuse) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => $accesRefuse,
            ]);
        }

        $request->session()->regenerate();

        $accueil = $user->role === 'super_admin'
            ? route('dashboard', absolute: false)
            : route('espace', absolute: false);

        return redirect()->intended($accueil);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
