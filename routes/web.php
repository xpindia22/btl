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



    // Lock & Restore Matches
    Route::post('/matches/lock-tournament', [MatchController::class, 'lockTournament'])->name('matches.lockTournament');
    Route::post('/matches/{id}/restore', [MatchController::class, 'restore'])->name('matches.restore');
    Route::delete('/matches/{id}/force-delete', [MatchController::class, 'forceDelete'])->name('matches.forceDelete');

    // Filtering Players for Matches
    Route::get('/matches/filtered-players', [MatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');

// Singles Matches Routes
Route::prefix('matches/singles')->group(function () {
    Route::get('/', [MatchController::class, 'indexSingles'])->name('matches.singles.index');
    Route::get('/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');
    Route::post('/store', [MatchController::class, 'storeSingles'])->name('matches.singles.store');

    // Lock/Unlock Tournament
    Route::post('/lock-tournament', [MatchController::class, 'lockSinglesTournament'])->name('matches.singles.lockTournament');
    Route::post('/unlock-tournament', [MatchController::class, 'unlockSinglesTournament'])->name('matches.singles.unlockTournament');

    // Fetch Players for Singles
    Route::get('/filtered-players', [MatchController::class, 'filteredPlayersSingles'])->name('matches.singles.filteredPlayers');

    // ✅ Correct Edit & Update Routes
    Route::get('/edit', [MatchController::class, 'editSingles'])->name('matches.singles.edit');
    Route::put('/{match}/update', [MatchController::class, 'updateSingles'])->name('matches.singles.update');

    // ✅ Delete Singles Match
    Route::delete('/{match}/delete', [MatchController::class, 'deleteSingles'])->name('matches.singles.delete');
});
 

// Results Management
Route::prefix('results')->group(function () {
    Route::get('/', [ResultsController::class, 'index'])->name('results.index');
    Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
    Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
    Route::get('/mixed-doubles', [ResultsController::class, 'mixedDoubles'])->name('results.mixed_doubles');
});

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['show']);});

    //doubles old git workign
     // -------------------------------------------------
    // DOUBLES ROUTES
    // ------------------------------------------------

// ...

Route::middleware(['auth'])->group(function () {
    Route::prefix('matches/doubles')->group(function () {
        
        // 1) View all doubles matches (read-only)
        Route::get('/', [DoublesMatchController::class, 'index'])
             ->name('matches.doubles.index');

        // 2) View doubles matches with edit/delete
        Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])
             ->name('matches.doubles.edit');

        // 3) Create a new doubles match
        Route::get('/create', [DoublesMatchController::class, 'createDoubles'])
             ->name('matches.doubles.create');

        // 4) Store a new doubles match
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])
             ->name('matches.doubles.store');

        // 5) Get filtered players based on BD/GD/XD
        Route::get('/filtered-players', [DoublesMatchController::class, 'getFilteredPlayers'])
             ->name('matches.doubles.filteredPlayers');

        // 6) Lock/unlock a tournament
        Route::post('/lock', [DoublesMatchController::class, 'lockTournament'])
             ->name('matches.doubles.lockTournament');
        Route::post('/unlock', [DoublesMatchController::class, 'unlockTournament'])
             ->name('matches.doubles.unlockTournament');

        // 7) Update multiple matches
        Route::put('/update-multiple', [DoublesMatchController::class, 'updateMultiple'])
             ->name('matches.doubles.updateMultiple');

        // 8) Update a single match
        Route::put('/{id}/update', [DoublesMatchController::class, 'update'])
             ->name('matches.doubles.update');

        // 9) Soft delete a match
        Route::delete('/{id}/delete', [DoublesMatchController::class, 'softDelete'])
             ->name('matches.doubles.delete');
    });
});
