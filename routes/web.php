<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('samuel.home');
});

Route::get('/samuel-app', function () {
    return view('samuel.home');
});

Route::get('/à-propos', function () {
    return view('samuel.about');
});

Route::get('/chapitres', function () {
    $chapitres = [
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

    return view('samuel.chapitres', compact('chapitres'));
})->name('chapitres');

Route::get('/chapitres/{numero}', function ($numero) {
    $chapitres = [
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

    abort_unless(isset($chapitres[$numero]), 404);

    return view('samuel.chapitre', [
        'numero' => $numero,
        'titre' => $chapitres[$numero],
    ]);
})->name('chapitre.show');

Route::get('/connexion', function () {
    return view('samuel.login');
});

Route::get('/inscription', function () {
    return view('samuel.register');
});

Route::post('/inscription', function () {
    // Traitement de l'inscription
    return back()->with('success', 'Votre inscription a été enregistrée avec succès. Vous recevrez un code d\'activation par email.');
})->name('registration.store');

Route::get('/activation/{code}', function ($code) {
    return view('samuel.activation', compact('code'));
});

Route::post('/verifier-code', function () {
    // Vérification du code d'activation
    return back()->with('success', 'Code vérifié avec succès');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            $derniere = \App\Models\Presence::max('date_repetition');
            $stats = [
                'total_samuels' => \App\Models\User::where('role', 'user')->count(),
                'total_administrateurs' => \App\Models\User::whereIn('role', ['admin', 'super_admin'])->count(),
                'inscriptions_en_attente' => \App\Models\User::where('status', 'pending')->count(),
                'derniere_repetition' => $derniere,
                'derniere_date' => $derniere ? \Illuminate\Support\Carbon::parse($derniere)->toDateString() : null,
            ];

            $stats['presents'] = \App\Models\Presence::whereDate('date_repetition', $stats['derniere_date'])->where('statut', 'present')->count();
            $stats['absents'] = \App\Models\Presence::whereDate('date_repetition', $stats['derniere_date'])->where('statut', 'absent')->count();
            $stats['retards'] = \App\Models\Presence::whereDate('date_repetition', $stats['derniere_date'])->where('statut', 'retard')->count();
            $stats['permissionnaires'] = \App\Models\Presence::whereDate('date_repetition', $stats['derniere_date'])->where('statut', 'permissionnaire')->count();
            $stats['total_derniere'] = $stats['presents'] + $stats['absents'] + $stats['retards'] + $stats['permissionnaires'];
            $stats['taux_participation'] = $stats['total_derniere'] > 0
                ? round((($stats['presents'] + $stats['retards']) / $stats['total_derniere']) * 100)
                : 0;

            $inscriptions_en_attente = \App\Models\User::where('status', 'pending')->orderByDesc('created_at')->limit(6)->get();
            $activites = \App\Models\Activity::with('user')->orderByDesc('created_at')->limit(8)->get();

            return view('samuel.dashboards.super-admin', compact('stats', 'inscriptions_en_attente', 'activites'));
        }

        if ($user->role === 'admin') {
            return view('samuel.dashboards.admin-designe');
        }

        return view('samuel.home');
    })->name('dashboard');

    Route::get('/profil', function () {
        return view('samuel.profil');
    })->name('profil');

    // --- Administration ---

    Route::get('/nouveau-samuel', function () {
        return view('samuel.admin.nouveau-samuel');
    });

    Route::post('/nouveau-samuel', function () {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8',
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'user',
            'status' => 'active',
        ]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'samuel.create',
            'description' => 'Nouveau Samuel ajouté : ' . $data['name'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Samuel ajouté avec succès.');
    });

    Route::get('/nouvel-admin', function () {
        return view('samuel.admin.nouvel-admin');
    });

    Route::post('/nouvel-admin', function () {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'admin',
            'status' => 'active',
        ]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'admin.create',
            'description' => 'Nouvel administrateur ajouté : ' . $data['name'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Administrateur ajouté avec succès.');
    });

    Route::get('/inscriptions', function () {
        $inscriptions = \App\Models\User::where('status', 'pending')->orderByDesc('created_at')->get();
        return view('samuel.admin.inscriptions', compact('inscriptions'));
    });

    Route::get('/samuels', function () {
        $samuels = \App\Models\User::where('role', 'user')->orderByDesc('created_at')->get();
        return view('samuel.admin.samuels', compact('samuels'));
    });

    Route::get('/administrateurs', function () {
        $administrateurs = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->orderByDesc('created_at')->get();
        return view('samuel.admin.administrateurs', compact('administrateurs'));
    });

    Route::get('/presences', function () {
        $presences = \App\Models\Presence::with('user')->orderByDesc('date_repetition')->get();
        return view('samuel.admin.presences', compact('presences'));
    });

    Route::get('/historique', function () {
        $activites = \App\Models\Activity::with('user')->orderByDesc('created_at')->get();
        return view('samuel.admin.historique', compact('activites'));
    });

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('/notifications/latest', [\App\Http\Controllers\NotificationController::class, 'latest']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead']);

    Route::get('/parametres', function () {
        return view('samuel.admin.parametres');
    });

    Route::post('/parametres', function () {
        $user = auth()->user();

        if (request()->filled('current_password')) {
            request()->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if (!\Illuminate\Support\Facades\Hash::check(request('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }

            $user->update(['password' => request('password')]);

            \App\Models\Activity::create([
                'user_id' => $user->id,
                'action' => 'password.change',
                'description' => 'Mot de passe modifié',
                'ip_address' => request()->ip(),
            ]);

            return back()->with('success', 'Mot de passe modifié avec succès.');
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        \App\Models\Activity::create([
            'user_id' => $user->id,
            'action' => 'profile.update',
            'description' => 'Profil mis à jour',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Profil mis à jour.');
    });

    Route::post('/parametres/photo', function () {
        $user = auth()->user();

        request()->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $path = request()->file('photo')->store('photos', 'public');
        $user->update(['photo' => $path]);

        \App\Models\Activity::create([
            'user_id' => $user->id,
            'action' => 'photo.update',
            'description' => 'Photo de profil mise à jour',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    });

    Route::get('/nouvelle-seance', function () {
        return view('samuel.admin.nouvelle-seance');
    });

    Route::post('/nouvelle-seance', function () {
        request()->validate([
            'date_repetition' => 'required|date',
            'remarque' => 'nullable|string|max:500',
        ]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'seance.create',
            'description' => 'Nouvelle séance créée pour le ' . \Carbon\Carbon::parse(request('date_repetition'))->format('d/m/Y'),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Séance créée avec succès.');
    });

    Route::post('/profil', function () {
        // Mise à jour du profil
        return back()->with('success', 'Profil mis à jour');
    })->name('profil.update');

    Route::post('/deconnexion', [
        'action' => 'App\Http\Controllers\Auth\SessionController::destroy',
        'as' => 'logout',
    ]);
});

require __DIR__.'/auth.php';