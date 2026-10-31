<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Un administrateur simple a son propre espace : on l'y renvoie
        // sans le déconnecter.
        if ($user && $user->role === 'admin' && $user->status === 'active') {
            return redirect()->route('espace');
        }

        if (! $user || $user->role !== 'super_admin' || $user->status !== 'active') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => "L'accès à cette page est réservé à l'administrateur principal."]);
        }

        return $next($request);
    }
}
