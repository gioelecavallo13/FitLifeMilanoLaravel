<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Coach\CoachController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTTE PUBBLICHE ---
Route::get('/', function () { return view('index'); })->name('home');
Route::get('/corsi', function () { return view('corsi'); })->name('corsi');
Route::get('/chi-siamo', function () { return view('chi-siamo'); })->name('chi-siamo');
Route::get('/contatti', function () { return view('contatti'); })->name('contatti');

Route::post('/contatti/store', [ContactRequestController::class, 'store'])->name('contact.store');

Route::middleware(['guest'])->group(function () {
    Route::get('/area-riservata', function () { 
        return view('area-riservata'); 
    })->name('login');

    Route::post('/login-process', [AuthController::class, 'login'])->name('login.process');
});


// --- ROTTE PROTETTE (Richiedono Login) ---
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard-selector', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard.selector');

    // --- GRUPPO AMMINISTRATORI ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Sottosezione Corsi
        Route::get('/courses/create', [AdminController::class, 'courseCreate'])->name('courses.create');
        Route::get('/courses/{id}', [AdminController::class, 'courseShow'])->name('courses.show');
        Route::post('/courses/store', [AdminController::class, 'courseStore'])->name('courses.store');
        Route::get('/courses/{id}/edit', [AdminController::class, 'courseEdit'])->name('courses.edit'); 
        Route::put('/courses/{id}', [AdminController::class, 'courseUpdate'])->name('courses.update');
        Route::post('/courses/destroy', [AdminController::class, 'courseDestroy'])->name('courses.destroy');

        // Sottosezione Messaggi
        Route::get('/messaggi', [AdminController::class, 'messages'])->name('messages.index');
        Route::get('/messaggi/{id}', [AdminController::class, 'messageShow'])->name('messages.show');
        Route::post('/messaggi/{id}/reply', [AdminController::class, 'messageReply'])->name('messages.reply');

        // Gestione Coach
        Route::get('/inserisci-coach', [AdminController::class, 'createCoach'])->name('coaches.create');
        Route::post('/store-coach', [AdminController::class, 'storeCoach'])->name('coaches.store');

        // Gestione Clienti
        Route::get('/inserisci-clienti', [AdminController::class, 'createClient'])->name('clients.create');
        Route::post('/store-clienti', [AdminController::class, 'storeClient'])->name('clients.store');

        // Gestione Utenti (Generale)
        Route::get('/utenti', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/utenti/{id}', [AdminController::class, 'userShow'])->name('users.show');
        Route::get('/utenti/{id}/modifica', [AdminController::class, 'userEdit'])->name('users.edit');
        Route::put('/utenti/{id}/aggiorna', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/utenti/{id}/elimina', [AdminController::class, 'userDestroy'])->name('users.destroy');
    });

    // --- GRUPPO COACH ---
    Route::middleware(['role:coach'])->prefix('coach')->name('coach.')->group(function () {
        Route::get('/dashboard', [CoachController::class, 'index'])->name('dashboard');
    });

    // --- GRUPPO CLIENTI ---
    Route::middleware(['role:client'])->prefix('client')->name('client.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');

        // Sezione Prenotazioni (Visualizzazione)
        Route::get('/prenota-corsi', [ClientController::class, 'booking'])->name('booking');
        Route::get('/corsi/{id}', [ClientController::class, 'courseShow'])->name('courses.show');

        // Azione di Prenotazione (Iscrizione)
        Route::post('/corsi/{courseId}/prenota', [ClientController::class, 'enroll'])->name('enroll');

        // Azione di Cancellazione Prenotazione
        Route::delete('/corsi/{courseId}/annulla', [ClientController::class, 'cancelBooking'])->name('cancel');
    });

});