<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SamuelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Application Mobile Flutter)
|--------------------------------------------------------------------------
*/

// --- Authentification mobile (public) ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// --- Mot de passe oublié (public, limité contre l'abus) ---
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:3,1');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:5,1');

// --- Chapitres (public) ---
Route::get('/chapitres', [SamuelController::class, 'index']);
Route::get('/chapitres/{id}', [SamuelController::class, 'show']);

// --- Activation de compte (public) ---
Route::post('/verify-code', [SamuelController::class, 'verifyCode']);

// --- Routes protégées par jeton ---
Route::middleware('api.token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profil
    Route::get('/profil', [SamuelController::class, 'getProfil']);
    Route::post('/profil', [SamuelController::class, 'updateProfil']);

    // Présences
    Route::get('/presences', [SamuelController::class, 'getPresences']);
    Route::post('/presences', [SamuelController::class, 'savePresence']);

    // Statistiques
    Route::get('/statistiques', [SamuelController::class, 'getStatistiques']);

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markRead']);
});

// --- Administrateur ---
Route::middleware(['api.token', 'api.role:admin,super_admin'])->group(function () {
    Route::get('/admin/samuels', [SamuelController::class, 'getSamuels']);
    Route::post('/admin/samuels', [SamuelController::class, 'storeSamuel']);
    Route::put('/admin/samuels/{id}', [SamuelController::class, 'updateSamuel']);
    Route::delete('/admin/samuels/{id}', [SamuelController::class, 'destroySamuel']);
});

// --- Super administrateur ---
Route::middleware(['api.token', 'api.role:super_admin'])->group(function () {
    Route::get('/admin/all-samuels', [SamuelController::class, 'getAllSamuels']);
    Route::put('/admin/samuels/{id}/role', [SamuelController::class, 'updateSamuelRole']);
});
