<?php

use App\Http\Controllers\Auth\PremiereConnexionController;
use App\Http\Controllers\EspaceController;
use App\Http\Controllers\NotificationController;

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

Route::get('/a-propos', function () {
    return view('samuel.about');
})->name('a-propos');

Route::get('/à-propos', function () {
    return view('samuel.about');
});

Route::get('/photo/{user}', function (\App\Models\User $user) {
    if ($user->photo_data && preg_match('#^data:(image/[a-zA-Z0-9.+-]+);base64,(.*)$#s', $user->photo_data, $m)) {
        return response(base64_decode($m[2]), 200)
            ->header('Content-Type', $m[1])
            ->header('Cache-Control', 'public, max-age=604800');
    }

    if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
        return \Illuminate\Support\Facades\Storage::disk('public')->response($user->photo);
    }

    abort(404);
})->name('photo.show');

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

    $chemin = resource_path("chapitres/{$numero}.html");
    $contenu = is_file($chemin) ? file_get_contents($chemin) : null;

    return view('samuel.chapitre', [
        'numero' => $numero,
        'titre' => $chapitres[$numero],
        'contenu' => $contenu,
    ]);
})->name('chapitre.show');

Route::get('/connexion', function () {
    return view('samuel.login');
});

Route::get('/premiere-connexion', [PremiereConnexionController::class, 'create'])->name('premiere-connexion');
Route::post('/premiere-connexion', [PremiereConnexionController::class, 'store']);

Route::get('/inscription', function () {
    return view('samuel.register');
});

Route::post('/inscription', function () {
    $data = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'],
        'role' => 'user',
        'status' => 'pending',
    ]);

    \App\Models\Notification::notifierAdmins(
        'Nouvelle inscription (web)',
        $user->name . ' s\'est inscrit depuis le site web. En attente de validation.',
        'info',
        'web',
        ['user_id' => $user->id]
    );

    return back()->with('success', 'Votre inscription a bien été enregistrée. Un administrateur va lui attribuer un rôle, puis vous recevrez votre code d\'activation par email.');
})->name('registration.store');

Route::get('/activation/{code}', function ($code) {
    return view('samuel.activation', compact('code'));
});

Route::post('/verifier-code', function () {
    // Vérification du code d'activation
    return back()->with('success', 'Code vérifié avec succès');
});

Route::middleware(['auth', 'web.admin'])->group(function () {
    Route::get('/espace', [EspaceController::class, 'index'])->name('espace');
    Route::get('/espace/samuels', [EspaceController::class, 'samuels'])->name('espace.samuels');
    Route::get('/espace/samuels/ajouter', [EspaceController::class, 'createSamuel'])->name('espace.samuels.create');
    Route::post('/espace/samuels', [EspaceController::class, 'storeSamuel'])->name('espace.samuels.store');
    Route::get('/espace/samuels/{id}/modifier', [EspaceController::class, 'editSamuel'])->name('espace.samuels.edit');
    Route::put('/espace/samuels/{id}', [EspaceController::class, 'updateSamuel'])->name('espace.samuels.update');
    Route::delete('/espace/samuels/{id}', [EspaceController::class, 'destroySamuel'])->name('espace.samuels.destroy');
    Route::get('/espace/presence', [EspaceController::class, 'presence'])->name('espace.presence');
    Route::post('/espace/presence', [EspaceController::class, 'storePresence'])->name('espace.presence.store');
    Route::get('/espace/chapitres/{numero}', [EspaceController::class, 'chapitre'])->name('espace.chapitre');
});

Route::middleware(['auth', 'web.super'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            $derniere = \App\Models\Presence::max('date_repetition');
            $stats = [
                'total_samuels' => \App\Models\Samuel::count(),
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
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
        ]);

        $samuel = \App\Models\Samuel::create([
            'nom' => trim($data['nom']),
            'prenoms' => trim($data['prenoms']),
        ]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'samuel.create',
            'description' => 'Nouveau Samuel ajouté : ' . $samuel->nom_complet,
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

    Route::post('/inscriptions/{id}/valider', function ($id) {
        $role = request()->validate([
            'role' => 'nullable|in:user,admin',
        ])['role'] ?? 'user';

        $user = \App\Models\User::where('status', 'pending')->findOrFail($id);

        $user->role = $role;

        if (!$user->activation_code) {
            $user->activation_code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        }

        $user->save();

        $envoye = false;
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\ActivationCodeMail($user->name, $user->activation_code));
            $envoye = true;
        } catch (\Throwable $e) {
            $envoye = false;
        }

        $roleLabel = $role === 'admin' ? 'Administrateur' : 'Simple Samuel';

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'inscription.valider',
            'description' => 'Inscription validée (' . $roleLabel . ') : ' . $user->name,
            'ip_address' => request()->ip(),
        ]);

        $message = 'Inscription de ' . $user->name . ' validée comme ' . $roleLabel . '. '
            . ($envoye
                ? 'Le code d\'activation a été envoyé par email à ' . $user->email . '.'
                : 'L\'email n\'a pas pu être envoyé — réessayez la validation pour le renvoyer.');

        return back()->with('success', $message);
    })->name('inscriptions.valider');

    Route::post('/inscriptions/{id}/rejeter', function ($id) {
        $user = \App\Models\User::where('status', 'pending')->findOrFail($id);
        $user->update(['status' => 'disabled', 'activation_code' => null]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'inscription.rejeter',
            'description' => 'Inscription rejetée : ' . $user->name,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Inscription de ' . $user->name . ' rejetée.');
    })->name('inscriptions.rejeter');

    Route::get('/samuels', function () {
        $samuels = \App\Models\Samuel::ordonne()->get();
        return view('samuel.admin.samuels', compact('samuels'));
    });

    Route::get('/samuels/liste-telecharger', function () {
        $user = auth()->user();
        if (! in_array($user->role, ['admin', 'super_admin'], true)) {
            abort(403);
        }

        $samuels = \App\Models\Samuel::ordonne()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('samuel.admin.samuels-liste-pdf', [
            'samuels' => $samuels,
        ])->setPaper('a4', 'portrait');

        \App\Models\Activity::create([
            'user_id' => $user->id,
            'action' => 'samuels.pdf',
            'description' => 'Liste des Samuels téléchargée (PDF)',
            'ip_address' => request()->ip(),
        ]);

        return $pdf->download('liste-samuels-' . now()->format('Y-m-d') . '.pdf');
    })->name('samuels.liste.pdf');

    Route::get('/samuels/{id}/modifier', function ($id) {
        $samuel = \App\Models\Samuel::findOrFail($id);

        return view('samuel.admin.modifier-samuel', compact('samuel'));
    })->name('samuels.edit');

    Route::put('/samuels/{id}', function ($id) {
        $samuel = \App\Models\Samuel::findOrFail($id);

        $data = request()->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'ordre' => 'nullable|integer|min:0|max:9999',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
            'ordre.integer' => "L'ordre d'affichage doit être un nombre entier.",
        ]);

        $avant = $samuel->nom_complet;

        $samuel->update([
            'nom' => trim($data['nom']),
            'prenoms' => trim($data['prenoms']),
            'ordre' => $data['ordre'] ?? $samuel->ordre,
        ]);

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'samuel.update',
            'description' => 'Samuel modifié : ' . $avant . ' → ' . $samuel->nom_complet,
            'ip_address' => request()->ip(),
        ]);

        return redirect('/samuels')->with('success', 'Samuel modifié avec succès.');
    })->name('samuels.update');

    Route::delete('/samuels/{id}', function ($id) {
        $samuel = \App\Models\Samuel::findOrFail($id);
        $nom = $samuel->nom_complet;

        $samuel->presences()->delete();
        $samuel->delete();

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'samuel.delete',
            'description' => 'Samuel supprimé : ' . $nom,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Samuel supprimé avec succès.');
    })->name('samuels.destroy');

    Route::get('/administrateurs', function () {
        $administrateurs = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->orderByDesc('created_at')->get();
        return view('samuel.admin.administrateurs', compact('administrateurs'));
    });

    Route::get('/presences', function () {
        // Une ligne par samedi, avec le récapitulatif des statuts.
        $dates = \App\Models\Presence::select('date_repetition')
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when statut = 'present' then 1 else 0 end) as presents")
            ->selectRaw("sum(case when statut = 'absent' then 1 else 0 end) as absents")
            ->selectRaw("sum(case when statut = 'retard' then 1 else 0 end) as retards")
            ->selectRaw("sum(case when statut = 'permissionnaire' then 1 else 0 end) as permissions")
            ->groupBy('date_repetition')
            ->orderByDesc('date_repetition')
            ->get();

        $signatures = \App\Models\PresenceSession::pluck('signed_at', 'date_repetition');

        return view('samuel.admin.presences', compact('dates', 'signatures'));
    });

    Route::post('/presences/{date}/signer', function ($date) {
        $user = auth()->user();
        if ($user->role !== 'super_admin') {
            abort(403);
        }

        $carbone = \Carbon\Carbon::parse($date);
        if ($carbone->dayOfWeek !== \Carbon\Carbon::SATURDAY) {
            abort(422);
        }

        \App\Models\PresenceSession::updateOrCreate(
            ['date_repetition' => $carbone->toDateString()],
            ['signed_at' => now(), 'signed_by' => $user->id]
        );

        return back()->with('success', 'Liste signée pour le ' . $carbone->format('d/m/Y') . '.');
    })->name('presences.signer');

    Route::get('/presences/pdf', function () {
        $date = \App\Support\DateRepetition::resoudre(request('date'));
        $dateIso = $date->toDateString();

        $user = auth()->user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Seul le super administrateur peut télécharger le document signé.');
        }

        $session = \App\Models\PresenceSession::where('date_repetition', $dateIso)->first();
        if (! $session) {
            return back()->withErrors(['pdf' => 'La liste doit être signée par le super administrateur avant de pouvoir être téléchargée.']);
        }

        $samuels = \App\Models\Samuel::ordonne()->get();
        $presences = \App\Models\Presence::whereDate('date_repetition', $dateIso)
            ->get()
            ->keyBy('samuel_id');

        $logo = public_path('images/logo-pdf.png');
        $logoBase64 = file_exists($logo)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo))
            : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('samuel.admin.presences-pdf', [
            'date' => $date,
            'dateIso' => $dateIso,
            'samuels' => $samuels,
            'presences' => $presences,
            'logo' => $logoBase64,
        ])->setPaper('a4', 'portrait');

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'presence.pdf',
            'description' => 'Liste de présence téléchargée (PDF) pour le ' . $date->format('d/m/Y'),
            'ip_address' => request()->ip(),
        ]);

        return $pdf->download('presences-' . $dateIso . '.pdf');
    })->name('presences.pdf');

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
        $user->update([
            'photo' => $path,
            'photo_data' => \App\Models\User::encodePhoto(request()->file('photo')),
        ]);

        \App\Models\Activity::create([
            'user_id' => $user->id,
            'action' => 'photo.update',
            'description' => 'Photo de profil mise à jour',
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    });

    Route::get('/nouvelle-seance', function () {
        $date = \App\Support\DateRepetition::resoudre(request('date'));

        $dateIso = $date->toDateString();

        $samuels = \App\Models\Samuel::ordonne()->get();
        $presences = \App\Models\Presence::whereDate('date_repetition', $dateIso)
            ->get()
            ->keyBy('samuel_id');

        return view('samuel.admin.nouvelle-seance', compact('dateIso', 'samuels', 'presences'));
    });

    Route::post('/nouvelle-seance', function () {
        $data = request()->validate([
            'date_repetition' => 'required|date',
            'statuts' => 'nullable|array',
            'statuts.*' => 'nullable|in:present,absent,retard,permissionnaire',
        ], [
            'date_repetition.required' => 'Veuillez choisir une date.',
            'date_repetition.date' => 'La date choisie est invalide.',
        ]);

        $carbon = \Carbon\Carbon::parse($data['date_repetition']);

        if ($carbon->dayOfWeek !== \Carbon\Carbon::SATURDAY) {
            return back()
                ->withErrors(['date_repetition' => 'La présence ne peut être enregistrée que le samedi.'])
                ->withInput();
        }

        $dateIso = $carbon->toDateString();
        $statuts = array_filter($data['statuts'] ?? [], fn ($v) => $v !== null && $v !== '');

        if (empty($statuts)) {
            return back()
                ->withErrors(['statuts' => 'Aucun statut n\'a été sélectionné.'])
                ->withInput();
        }

        $enregistres = 0;

        foreach ($statuts as $samuelId => $statut) {
            $samuel = \App\Models\Samuel::find($samuelId);
            if (! $samuel) {
                continue;
            }

            // On cherche par jour (whereDate) car la colonne est un datetime :
            // comparer directement '2026-10-31' ne matcherait pas '2026-10-31 00:00:00'
            // et provoquerait une violation de la contrainte d'unicité.
            $presence = \App\Models\Presence::where('samuel_id', $samuel->id)
                ->whereDate('date_repetition', $dateIso)
                ->first();

            if ($presence) {
                $presence->update([
                    'statut' => $statut,
                    'synchronise' => true,
                ]);
            } else {
                \App\Models\Presence::create([
                    'samuel_id' => $samuel->id,
                    'date_repetition' => $dateIso,
                    'statut' => $statut,
                    'synchronise' => true,
                ]);
            }

            $enregistres++;
        }

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'presence.create',
            'description' => $enregistres . ' présence(s) enregistrée(s) le ' . $carbon->format('d/m/Y'),
            'ip_address' => request()->ip(),
        ]);

        return redirect('/presences')->with(
            'success',
            $enregistres . ' présence(s) enregistrée(s) pour le ' . $carbon->format('d/m/Y') . '.'
        );
    })->name('presences.store');

    Route::post('/profil', function () {
        // Mise à jour du profil
        return back()->with('success', 'Profil mis à jour');
    })->name('profil.update');
});

require __DIR__.'/auth.php';