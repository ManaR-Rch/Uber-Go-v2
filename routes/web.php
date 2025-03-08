<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\DisponibiliteController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification (générées par Laravel Breeze/Jetstream)
// Auth::routes();

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', function () {
    return view('home');
})->name('home');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Routes du profil
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes des trajets
    Route::get('/trajets', function () {
        return view('trajets.index');
    })->name('trajets.index');
    
    Route::get('/trajets/create', function () {
        return view('trajets.create');
    })->name('trajets.create');
    
    Route::post('/trajets', [TrajetController::class, 'store'])->name('trajets.store');
    Route::get('/trajets/{id}', [TrajetController::class, 'show'])->name('trajets.show');
    Route::get('/trajets/{id}/edit', [TrajetController::class, 'edit'])->name('trajets.edit');
    Route::put('/trajets/{id}', [TrajetController::class, 'update'])->name('trajets.update');
    Route::delete('/trajets/{id}', [TrajetController::class, 'destroy'])->name('trajets.destroy');
    
    // Routes de recherche de trajets
    Route::get('/search', [TrajetController::class, 'search'])->name('trajets.search');
    
    // Routes de réservation
    Route::get('/reservations', function () {
        return view('reservations.index');
    })->name('reservations.index');
    
    Route::post('/trajets/{id}/reserver', [TrajetController::class, 'reserver'])->name('trajets.reserver');
    Route::delete('/trajets/{id}/annuler', [TrajetController::class, 'annulerReservation'])->name('trajets.annulerReservation');
    
    // Routes des disponibilités (accessibles uniquement pour les chauffeurs)
    Route::middleware(function ($request, $next) {
        if (auth()->user()->role !== 'chauffeur') {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé');
        }
        return $next($request);
    })->group(function () {
        Route::get('/disponibilites', [DisponibiliteController::class, 'index'])->name('disponibilites.index');
        Route::get('/disponibilites/create', [DisponibiliteController::class, 'create'])->name('disponibilites.create');
        Route::post('/disponibilites', [DisponibiliteController::class, 'store'])->name('disponibilites.store');
        Route::get('/disponibilites/{id}', [DisponibiliteController::class, 'show'])->name('disponibilites.show');
        Route::get('/disponibilites/{id}/edit', [DisponibiliteController::class, 'edit'])->name('disponibilites.edit');
        Route::put('/disponibilites/{id}', [DisponibiliteController::class, 'update'])->name('disponibilites.update');
        Route::delete('/disponibilites/{id}', [DisponibiliteController::class, 'destroy'])->name('disponibilites.destroy');
    });
    
    // Route de recherche de disponibilités
    Route::get('/disponibilites/search', [DisponibiliteController::class, 'search'])->name('disponibilites.search');
    
    // Routes des notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear', [NotificationController::class, 'clearRead'])->name('notifications.clearRead');
    Route::get('/notifications/unread', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});