<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\DoublesMatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;

// --------------------------------------------------
// ROOT REDIRECT
// --------------------------------------------------
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// --------------------------------------------------
// AUTH ROUTES
// --------------------------------------------------
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// --------------------------------------------------
// PUBLIC PLAYER REGISTRATION & VIEWING
// --------------------------------------------------
Route::middleware(['web'])->group(function () {
    Route::get('/players/register', [PlayerController::class, 'create'])->name('players.register');
    Route::post('/players/register', [PlayerController::class, 'register'])->name('players.register.post');
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{uid}/update', [PlayerController::class, 'update']);
    Route::put('/players/{uid}/update', [PlayerController::class, 'update'])->name('players.update');

    Route::delete('/players/{uid}/delete', [PlayerController::class, 'destroy']);
});

// --------------------------------------------------
// PROTECTED ROUTES (AUTH REQUIRED)
// --------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --------------------------
    // TOURNAMENTS
    // --------------------------
    Route::resource('tournaments', TournamentController::class)->except(['show']);

    // --------------------------
    // USERS
    // --------------------------
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/edit', [UserController::class, 'editUsers'])->name('users.edit');

    // --------------------------
    // ADMIN (ADMIN-ONLY)
    // --------------------------
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        // example resource route – adapt as needed
        Route::resource('admin/edit_users', AdminController::class)->except(['create', 'store', 'show']);
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');

        // ✅ Admin Password Reset Route
        Route::get('/password-resets', [AdminPasswordResetController::class, 'index'])->name('admin.password-resets');
    });

    // --------------------------
    // GENERAL MATCH ROUTES
    // --------------------------
    Route::post('/matches/lock-tournament', [MatchController::class, 'lockTournament'])->name('matches.lockTournament');
    Route::post('/matches/{id}/restore', [MatchController::class, 'restore'])->name('matches.restore');
    Route::delete('/matches/{id}/force-delete', [MatchController::class, 'forceDelete'])->name('matches.forceDelete');
    Route::get('/matches/filtered-players', [MatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');

    // --------------------------
    // SINGLES MATCHES
    // --------------------------
    Route::prefix('matches/singles')->group(function () {
        Route::get('/', [MatchController::class, 'indexSingles'])->name('matches.singles.index');
        Route::get('/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');
        Route::post('/store', [MatchController::class, 'storeSingles'])->name('matches.singles.store');
        Route::post('/lock-tournament', [MatchController::class, 'lockSinglesTournament'])->name('matches.singles.lockTournament');
        Route::post('/unlock-tournament', [MatchController::class, 'unlockSinglesTournament'])->name('matches.singles.unlockTournament');
        Route::get('/filtered-players', [MatchController::class, 'filteredPlayersSingles'])->name('matches.singles.filteredPlayers');
        Route::get('/{match}/edit', [MatchController::class, 'editSingles'])->name('matches.singles.edit');
        Route::put('/{match}/update', [MatchController::class, 'updateSingles'])->name('matches.singles.update');
        Route::delete('/{match}/delete', [MatchController::class, 'deleteSingles'])->name('matches.singles.delete');
    });

    // --------------------------
    // DOUBLES MATCH ROUTES
    // --------------------------
    Route::prefix('matches/doubles')->group(function () {
        Route::get('/', [DoublesMatchController::class, 'index'])->name('matches.doubles.index');
        Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])->name('matches.doubles.edit');
        Route::get('/create', [DoublesMatchController::class, 'createDoubles'])->name('matches.doubles.create');
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])->name('matches.doubles.store');
        Route::get('/filtered-players', [DoublesMatchController::class, 'getFilteredPlayers'])->name('matches.doubles.filteredPlayers');
        Route::post('/lock', [DoublesMatchController::class, 'lockTournament'])->name('matches.doubles.lockTournament');
        Route::post('/unlock', [DoublesMatchController::class, 'unlockTournament'])->name('matches.doubles.unlockTournament');
        Route::put('/{match}/update', [DoublesMatchController::class, 'update'])->name('matches.doubles.update');
        Route::delete('/{match}/delete', [DoublesMatchController::class, 'softDelete'])->name('matches.doubles.delete');
    });

    // --------------------------
    // CATEGORIES
    // --------------------------
    Route::resource('categories', CategoryController::class)->except(['show']);

    // --------------------------
    // PROFILE SETTINGS (EDIT USER DETAILS)
    // --------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// --------------------------------------------------
// RESULTS
// --------------------------------------------------
Route::prefix('results')->group(function () {
    Route::get('/singles', [MatchController::class, 'showSinglesResults'])->name('results.singles');
    Route::get('/doubles', [MatchController::class, 'showDoublesResults'])->name('results.doubles');
});

// --------------------------------------------------
// PASSWORD RESET (FORGOT PASSWORD)
// --------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::prefix('matches/doubles')->group(function () {

        // 1) Read-only index
        Route::get('/', [DoublesMatchController::class, 'index'])
             ->name('matches.doubles.index');

        // 2) “Edit” view (shows a table w/ inline editing & delete)
        Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])
             ->name('matches.doubles.edit');

        // 3) Create doubles
        Route::get('/create', [DoublesMatchController::class, 'createDoubles'])
             ->name('matches.doubles.create');

        // 4) Store doubles
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])
             ->name('matches.doubles.store');

        // 5) Filtered players for doubles (BD, GD, XD)
        Route::get('/filtered-players', [DoublesMatchController::class, 'getFilteredPlayers'])
             ->name('matches.doubles.filteredPlayers');

        // 6) Lock/unlock tournament
        Route::post('/lock', [DoublesMatchController::class, 'lockTournament'])
             ->name('matches.doubles.lockTournament');
        Route::post('/unlock', [DoublesMatchController::class, 'unlockTournament'])
             ->name('matches.doubles.unlockTournament');

        // 7) Update one doubles match (inline editing)
        Route::put('/{match}/update', [DoublesMatchController::class, 'update'])
             ->name('matches.doubles.update');

        // 8) Soft delete one doubles match
        Route::delete('/{match}/delete', [DoublesMatchController::class, 'softDelete'])
             ->name('matches.doubles.delete');
    });
});
