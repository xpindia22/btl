<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;

// Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// Player Registration
Route::prefix('players')->group(function () {
    Route::get('/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
    Route::post('/register', [PlayerController::class, 'register']);
});

// Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tournaments Management
    Route::resource('tournaments', TournamentController::class)->except(['show']);

    // User Management (Fix: Removed 'show' method)
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/edit', [UserController::class, 'editUsers'])->name('users.edit');

    // Admin Panel (Only for Admins)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/edit_users', AdminController::class)->except(['create', 'store', 'show']);
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });

    // Matches Management (Singles & Doubles in MatchController)
    Route::resource('matches', MatchController::class)->except(['show']);

    // Lock & Restore Matches
    Route::post('/matches/lock-tournament', [MatchController::class, 'lockTournament'])->name('matches.lockTournament');
    Route::post('/matches/{id}/restore', [MatchController::class, 'restore'])->name('matches.restore');
    Route::delete('/matches/{id}/force-delete', [MatchController::class, 'forceDelete'])->name('matches.forceDelete');

    // Filtering Players for Matches
    Route::get('/matches/filtered-players', [MatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');

    // Singles Matches
    Route::get('/matches/singles/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');
    Route::get('/matches/singles', [MatchController::class, 'indexSingles'])->name('matches.singles.index');
    Route::get('/matches/singles/{match}/edit', [MatchController::class, 'editSingles'])->name('matches.singles.edit');

    // Doubles Matches
    Route::get('/matches/doubles/create', [MatchController::class, 'createDoubles'])->name('matches.doubles.create');
    Route::get('/matches/doubles', [MatchController::class, 'indexDoubles'])->name('matches.doubles.index');
    Route::get('/matches/doubles/{match}/edit', [MatchController::class, 'editDoubles'])->name('matches.doubles.edit');

    // Results
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [ResultsController::class, 'mixedDoubles'])->name('results.mixed_doubles');
    });

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['show']);

});
