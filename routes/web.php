<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SinglesMatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\DoublesBoysMatchController;
use App\Http\Controllers\DoublesGirlsMatchController;
use App\Http\Controllers\DoublesMixedMatchController;
use App\Http\Controllers\DoublesMatchController;
use App\Models\MatchDoubles;


// Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// Player Registration
Route::get('/players/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
Route::post('/players/register', [PlayerController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Matches Routes (Generic)
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('matches.index');
        Route::post('/', [MatchController::class, 'store'])->name('matches.store');
        Route::get('/create', [MatchController::class, 'create'])->name('matches.create');
    });

    // Doubles Boys
    Route::prefix('matches/doubles_boys')->group(function () {
        Route::get('/', [DoublesBoysMatchController::class, 'indexViewOnly'])->name('matches.doubles_boys.index');
        Route::get('/edit', [DoublesBoysMatchController::class, 'indexWithEdit'])->name('matches.doubles_boys.edit');
        Route::get('/matches/doubles_boys/create', [MatchController::class, 'createDoublesBoys'])
             ->name('matches.doubles_boys.create');
        Route::get('/matches/doubles_mixed/create', [MatchController::class, 'createDoublesMixed'])
             ->name('matches.doubles_mixed.create');
    });

    // Doubles Girls
    Route::prefix('matches/doubles_girls')->group(function () {
        Route::get('/', [DoublesGirlsMatchController::class, 'indexViewOnly'])->name('matches.doubles_girls.index');
        Route::get('/edit', [DoublesGirlsMatchController::class, 'indexWithEdit'])->name('matches.doubles_girls.edit');
        Route::get('/matches/doubles/create', [DoublesGirlsMatchController::class, 'create'])
             ->name('matches.doubles.create');
        Route::get('/matches/doubles', [DoublesGirlsMatchController::class, 'index'])
             ->name('matches.doubles.index');
    });

    // Doubles Mixed
    Route::prefix('matches/doubles_mixed')->group(function () {
        Route::get('/', [DoublesMixedMatchController::class, 'indexViewOnly'])->name('matches.doubles_mixed.index');
        Route::get('/edit', [DoublesMixedMatchController::class, 'indexWithEdit'])->name('matches.doubles_mixed.edit');
    });

    // Tournaments
    Route::prefix('tournaments')->group(function () {
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::get('/manage', [TournamentController::class, 'index'])->name('tournaments.manage');
        Route::get('/{id}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
        Route::put('/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
        Route::delete('/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    });

    // User Management (Admin)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Get Players (optional)
    Route::get('/get_players', [PlayerController::class, 'getPlayers'])->name('get_players');

    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });

    // Category
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Results
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [DoublesMixedMatchController::class, 'index'])->name('results.mixed_doubles');
        Route::get('/boys-doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');
    });

    // -----------------------------------------------------------------------------------------
    // SINGLES MATCH ROUTES
    // -----------------------------------------------------------------------------------------
    Route::prefix('matches/singles')->group(function () {
        // View Only
        Route::get('/', [SinglesMatchController::class, 'indexSingles'])->name('matches.singles.index');

        // View With Edit/Delete
        Route::get('/edit', [SinglesMatchController::class, 'indexSinglesWithEdit'])->name('matches.singles.edit');

        // Create
        Route::get('/create', [SinglesMatchController::class, 'createSingles'])->name('matches.singles.create');
        Route::post('/store', [SinglesMatchController::class, 'storeSingles'])->name('matches.singles.store');

        // Lock/Unlock Tournaments
        Route::post('/lock', [SinglesMatchController::class, 'lockTournament'])->name('matches.singles.lockTournament');
        Route::post('/unlock', [SinglesMatchController::class, 'unlockTournament'])->name('matches.singles.unlockTournament');

        // Edit a Single Match
        Route::get('/edit/{id}', [SinglesMatchController::class, 'editSingleMatch'])->name('matches.singles.editSingle');

        // Update a Single Match
        Route::put('/matches/singles/update/{id}', [SinglesMatchController::class, 'updateSingle'])
             ->name('matches.singles.updateSingle');

        // Delete a Single Match
        Route::delete('/delete/{id}', [SinglesMatchController::class, 'deleteSingleMatch'])
             ->name('matches.singles.deleteSingle');
    });

    // -----------------------------------------------------------------------------------------
    // AJAX route for filtering players in singles creation
    // -----------------------------------------------------------------------------------------
    Route::get('/matches/filtered-players', [SinglesMatchController::class, 'getFilteredPlayers'])
         ->name('matches.filteredPlayers');

    // Redirecting doubles routes
    Route::redirect('/matches/doubles-girls', '/matches/doubles_girls', 301);
    Route::redirect('/matches/doubles-boys', '/matches/doubles_boys', 301);
    Route::redirect('/matches/doubles-mixed', '/matches/doubles_mixed', 301);
    Route::redirect('/matches/doubles-girls/edit', '/matches/doubles_girls/edit', 301);
    Route::redirect('/matches/doubles-boys/edit', '/matches/doubles_boys/edit', 301);
    Route::redirect('/matches/doubles-mixed/edit', '/matches/doubles_mixed/edit', 301);


// Doubles BD, GD, XD matches
Route::prefix('matches/doubles')->group(function() {
    // Route::get('/', [DoublesMatchController::class, 'indexViewOnly'])->name('matches.doubles.index');
    
    Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])->name('matches.doubles.edit');

    Route::get('/create', [DoublesMatchController::class, 'createDoubles'])->name('matches.doubles.create');
    Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])->name('matches.doubles.store');

    // For filtered players
    Route::get('/filtered-players', [DoublesMatchController::class, 'getFilteredPlayers'])
         ->name('matches.doubles.filteredPlayers');

    // Add lock/unlock routes if doubles require tournament locking:
    Route::post('/lock', [DoublesMatchController::class, 'lockTournament'])->name('matches.doubles.lockTournament');
    Route::post('/unlock', [DoublesMatchController::class, 'unlockTournament'])->name('matches.doubles.unlockTournament');

    Route::prefix('matches/doubles')->group(function() {
        // ... other routes ...
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])->name('matches.doubles.store');
    });
    
});





});
