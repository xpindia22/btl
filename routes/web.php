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
use App\Http\Controllers\DoublesBoysMatchController;
use App\Http\Controllers\DoublesGirlsMatchController;
use App\Http\Controllers\DoublesMixedMatchController;

// 🔹 Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 🔹 Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 🔹 Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// 🔹 Fix for `player.register` Route
Route::get('/players/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
Route::post('/players/register', [PlayerController::class, 'register']);

// 🔹 Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔹 Matches Routes
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('matches.index');
        Route::post('/', [MatchController::class, 'store'])->name('matches.store');
        Route::get('/create', [MatchController::class, 'create'])->name('matches.create');
    });

    // 🔹 Singles Matches (View Only & Edit Mode)
    Route::prefix('matches/singles')->group(function () {
        Route::get('/', [MatchController::class, 'indexSinglesViewOnly'])->name('matches.singles.index'); // No edit/delete
        Route::get('/edit', [MatchController::class, 'indexSinglesWithEdit'])->name('matches.singles.edit'); // With edit/delete
        // Route for viewing the singles match creation form
        Route::get('/matches/singles/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');

        // Route for inserting a singles match
        Route::post('/matches/singles/store', [MatchController::class, 'insertSinglesMatch'])->name('matches.singles.store');

    });

    // 🔹 Doubles Boys Matches (View Only & Edit Mode)
    Route::prefix('matches/doubles_boys')->group(function () {
        Route::get('/', [DoublesBoysMatchController::class, 'indexViewOnly'])->name('matches.doubles_boys.index'); // No edit/delete
        Route::get('/edit', [DoublesBoysMatchController::class, 'indexWithEdit'])->name('matches.doubles_boys.edit'); // With edit/delete
        Route::get('/matches/doubles_boys/create', [MatchesController::class, 'createDoublesBoys'])
        ->name('matches.doubles_boys.create');
        Route::get('/matches/doubles_mixed/create', [MatchesController::class, 'createDoublesMixed'])
     ->name('matches.doubles_mixed.create');
         
    });

    // 🔹 Doubles Girls Matches (View Only & Edit Mode)
    Route::prefix('matches/doubles_girls')->group(function () {
        Route::get('/', [DoublesGirlsMatchController::class, 'indexViewOnly'])->name('matches.doubles_girls.index'); // No edit/delete
        Route::get('/edit', [DoublesGirlsMatchController::class, 'indexWithEdit'])->name('matches.doubles_girls.edit'); // With edit/delete
        Route::get('/matches/doubles/create', [DoublesGirlsMatchController::class, 'create'])
        ->name('matches.doubles.create');
   
   Route::get('/matches/doubles', [DoublesGirlsMatchController::class, 'index'])
        ->name('matches.doubles.index');
    
    
    });

    // 🔹 Doubles Mixed Matches (View Only & Edit Mode)
    Route::prefix('matches/doubles_mixed')->group(function () {
        Route::get('/', [DoublesMixedMatchController::class, 'indexViewOnly'])->name('matches.doubles_mixed.index'); // No edit/delete
        Route::get('/edit', [DoublesMixedMatchController::class, 'indexWithEdit'])->name('matches.doubles_mixed.edit'); // With edit/delete
    });

    // 🔹 Tournament Management
    Route::prefix('tournaments')->group(function () {
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::get('/manage', [TournamentController::class, 'index'])->name('tournaments.manage');
        Route::get('/{id}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
        Route::put('/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
        Route::delete('/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    });

    // 🔹 User Management (For Admin)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 🔹 Get Players Route
    Route::get('/get_players', [PlayerController::class, 'getPlayers'])->name('get_players');

    // 🔹 Admin Routes (Only Admins Can Access)
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });

    // 🔹 Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [DoublesMixedMatchController::class, 'index'])->name('results.mixed_doubles');
        Route::get('/boys-doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');
    });

    Route::redirect('/matches/doubles-girls', '/matches/doubles_girls', 301);
    Route::redirect('/matches/doubles-boys', '/matches/doubles_boys', 301);
    Route::redirect('/matches/doubles-mixed', '/matches/doubles_mixed', 301);

     

});
