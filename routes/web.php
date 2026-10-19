<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROUTE TEMPORAIRE : reinitialisation complete de la base (a retirer ensuite).
Route::get('/__db-reset/{token}', function (string $token) {
    abort_unless($token === 'SamuelReset2026xK9p', 404);

    $log = [];
    $run = function (string $label, callable $cb) use (&$log) {
        try {
            $cb();
        } catch (\Throwable $e) {
            $log[$label . ' ERREUR'] = get_class($e) . ': ' . $e->getMessage();
            try {
                $out = trim(\Illuminate\Support\Facades\Artisan::output());
                if ($out !== '') {
                    $log[$label . ' SORTIE'] = $out;
                }
            } catch (\Throwable $e2) {
            }
        }
    };

    $log['php'] = PHP_VERSION;
    $log['db_driver'] = config('database.default');
    $host = config('database.connections.pgsql.host');
    $log['host_initial'] = $host;

    if (request('direct')) {
        $direct = str_replace('-pooler', '', (string) $host);
        config(['database.connections.pgsql.host' => $direct]);
        \Illuminate\Support\Facades\DB::purge('pgsql');
        $log['host_utilise'] = $direct;
    }

    $run('db_connect', function () use (&$log) {
        \Illuminate\Support\Facades\DB::select('select 1');
        $log['db_connect'] = 'OK';
    });

    $run('tables', function () use (&$log) {
        $rows = \Illuminate\Support\Facades\DB::select("select table_name from information_schema.tables where table_schema='public'");
        $log['tables'] = implode(', ', array_map(fn ($r) => $r->table_name, $rows));
    });

    $run('users_columns', function () use (&$log) {
        $rows = \Illuminate\Support\Facades\DB::select("select column_name from information_schema.columns where table_name='users'");
        $log['users_columns'] = implode(', ', array_map(fn ($r) => $r->column_name, $rows));
    });

    if (request('mode') === 'migrate') {
        $run('migrate', function () use (&$log) {
            $code = \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $log['migrate'] = "code=$code\n" . trim(\Illuminate\Support\Facades\Artisan::output());
        });
    } else {
        $run('migrate:fresh', function () use (&$log) {
            $code = \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
            $log['migrate:fresh'] = "code=$code\n" . trim(\Illuminate\Support\Facades\Artisan::output());
        });
    }

    $run('seed', function () use (&$log) {
        $code = \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\SuperAdminSeeder', '--force' => true]);
        $log['seed'] = "code=$code\n" . trim(\Illuminate\Support\Facades\Artisan::output());
    });

    $run('resultat', function () use (&$log) {
        $log['utilisateurs'] = \App\Models\User::count();
        $log['super_admin'] = optional(\App\Models\User::where('role', 'super_admin')->first())->email ?? 'AUCUN';
    });

    if (request('test')) {
        $test = [];
        $try = function (string $label, callable $cb) use (&$test) {
            try {
                $res = $cb();
                $test[$label] = is_bool($res) ? ($res ? 'true' : 'false') : ($res ?? 'null');
            } catch (\Throwable $e) {
                $test[$label] = get_class($e) . ': ' . $e->getMessage();
            }
        };

        $try('cache_store', fn () => config('cache.default'));
        $try('session_driver', fn () => config('session.driver'));
        $try('rate_limiter', fn () => \Illuminate\Support\Facades\RateLimiter::tooManyAttempts('test|127.0.0.1', 5) ? 'limited' : 'not');
        $try('cache_write', function () {
            \Illuminate\Support\Facades\Cache::put('samuel_test', 'ok', 60);
            return \Illuminate\Support\Facades\Cache::get('samuel_test');
        });
        $try('user_role', fn () => optional(\App\Models\User::where('email', 'charbeltcym@gmail.com')->first())->role ?? 'INTROUVABLE');
        $try('hash_check', function () {
            $u = \App\Models\User::where('email', 'charbeltcym@gmail.com')->first();
            return $u && \Illuminate\Support\Facades\Hash::check('12345678', $u->password) ? 'OK' : 'NON';
        });
        $try('auth_attempt', fn () => \Illuminate\Support\Facades\Auth::attempt(['email' => 'charbeltcym@gmail.com', 'password' => '12345678']));
        $try('login_request', function () {
            $req = \App\Http\Requests\Auth\LoginRequest::create('/login', 'POST', ['email' => 'charbeltcym@gmail.com', 'password' => '12345678']);
            $req->setContainer(app())->setRedirector(app('redirect'));
            $req->validateResolved();
            $req->authenticate();
            return 'OK';
        });
        $log['TEST'] = $test;
    }

    if (request('test2')) {
        $t = [];
        $try = function (string $label, callable $cb) use (&$t) {
            try {
                $res = $cb();
                $t[$label] = is_bool($res) ? ($res ? 'true' : 'false') : (is_scalar($res) ? (string) $res : 'ok');
            } catch (\Throwable $e) {
                $t[$label] = get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
            }
        };

        $try('http_subrequest_login', function () {
            $prev = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
            app()->instance(\Illuminate\Contracts\Debug\ExceptionHandler::class, new class implements \Illuminate\Contracts\Debug\ExceptionHandler {
                public function report(\Throwable $e)
                {
                }

                public function render($request, \Throwable $e)
                {
                    throw $e;
                }

                public function renderForConsole($output, \Throwable $e)
                {
                }
            });

            try {
                $sub = \Illuminate\Http\Request::create('/login', 'POST', [
                    '_token' => csrf_token(),
                    'email' => 'charbeltcym@gmail.com',
                    'password' => '12345678',
                ]);
                $sub->setLaravelSession(app('session.store'));
                $resp = app(\Illuminate\Contracts\Http\Kernel::class)->handle($sub);
                return 'status=' . $resp->getStatusCode();
            } finally {
                app()->instance(\Illuminate\Contracts\Debug\ExceptionHandler::class, $prev);
            }
        });

        $try('route_dashboard', fn () => route('dashboard', absolute: false));
        $try('session_regenerate', fn () => app('session.store')->regenerate() ? 'ok' : 'x');

        $log['TEST2'] = $t;
    }

    $logFile = storage_path('logs/laravel.log');
    $log['log_tail'] = is_file($logFile) ? substr(file_get_contents($logFile), -5000) : '(aucun log)';

    return response('<pre>' . e(print_r($log, true)) . '</pre>');
});

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

    return back()->with('success', 'Votre inscription a bien été enregistrée. Un administrateur va la valider, puis vous recevrez votre code d\'activation par email.');
})->name('registration.store');

Route::get('/activation/{code}', function ($code) {
    return view('samuel.activation', compact('code'));
});

Route::post('/verifier-code', function () {
    // Vérification du code d'activation
    return back()->with('success', 'Code vérifié avec succès');
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
        $user = \App\Models\User::where('status', 'pending')->findOrFail($id);

        if (!$user->activation_code) {
            $user->activation_code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->save();
        }

        $envoye = false;
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\ActivationCodeMail($user->name, $user->activation_code));
            $envoye = true;
        } catch (\Throwable $e) {
            $envoye = false;
        }

        \App\Models\Activity::create([
            'user_id' => auth()->id(),
            'action' => 'inscription.valider',
            'description' => 'Inscription validée : ' . $user->name,
            'ip_address' => request()->ip(),
        ]);

        $message = 'Inscription de ' . $user->name . ' validée. Code : ' . $user->activation_code
            . ($envoye ? ' (envoyé par email à ' . $user->email . ')' : ' (email non envoyé, à transmettre manuellement)');

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
        $samuels = \App\Models\Samuel::orderByDesc('created_at')->get();
        return view('samuel.admin.samuels', compact('samuels'));
    });

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
        $presences = \App\Models\Presence::with('samuel')->orderByDesc('date_repetition')->get();
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
});

require __DIR__.'/auth.php';