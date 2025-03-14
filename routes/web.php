<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SinglesMatchController;
use App\Http\Controllers\DoublesMatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PaymentController;



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

Route::controller(UserRegisterController::class)->group(function () {
    Route::get('/users/create', [UserRegisterController::class, 'showRegistrationForm'])->name('users.create');
Route::post('/users/create', [UserRegisterController::class, 'register'])->name('users.store'); // ✅ Add this
});

// --------------------------------------------------
// PUBLIC PLAYER REGISTRATION & VIEWING
// --------------------------------------------------
Route::middleware(['web'])->group(function () {
    
    // Registration routes
    Route::get('/players/register', [PlayerController::class, 'create'])->name('players.register');
    Route::post('/players/register', [PlayerController::class, 'register'])->name('players.register.post');


// Choice page for rankings.
Route::get('/players/ranking', function () {
    return view('players.ranking_choice');
})->name('players.ranking_choice');

// Singles ranking page.
Route::get('/players/singles-ranking', [PlayerController::class, 'ranking'])
    ->name('players.ranking');

// Doubles ranking page.
Route::get('/players/doubles-ranking', [PlayerController::class, 'doublesRanking'])
    ->name('players.doublesRanking');


    // Player listing
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/{uid}/edit', [PlayerController::class, 'edit'])->name('players.editid');

    // Player CRUD operations (edit, update, delete)
    Route::get('/players/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{uid}/update', [PlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{uid}/delete', [PlayerController::class, 'destroy'])->name('players.delete');

});


// --------------------------------------------------
// PROTECTED ROUTES (AUTH REQUIRED)
// --------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



// Users Routes
Route::get('/users/edit', [UserController::class, 'editUsers'])->name('users.edit');
Route::resource('users', UserController::class)->except(['show', 'edit']);
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');



    // --------------------------
    // ADMIN (ADMIN-ONLY)
    // --------------------------
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');

        // ✅ Admin Password Reset Route (SHOW ALL RESET LINKS)
        Route::get('/password-resets', [UserController::class, 'showPasswordResets'])->name('admin.password-resets');
        // ✅ Admin Delete Password Reset Link
        Route::delete('/password-resets/{email}', [UserController::class, 'deletePasswordReset'])
        ->name('admin.deletePasswordReset');

    });

 
     
 //Tournaments

 Route::resource('tournaments', TournamentController::class)->except(['show']);
 Route::post('/tournaments/{id}/assign-categories', [TournamentController::class, 'assignCategories'])->name('tournaments.assignCategories');
 Route::get('/tournaments/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
 Route::put('/tournaments/update/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
 Route::delete('/tournaments/delete/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy'); // ✅ Make sure this exists
 
    // --------------------------
    // GENERAL MATCH ROUTES
    // --------------------------
    Route::post('/matches/lock-tournament', [SinglesMatchController::class, 'lockTournament'])->name('matches.lockTournament');
    Route::post('/matches/{id}/restore', [SinglesMatchController::class, 'restore'])->name('matches.restore');
    Route::delete('/matches/{id}/force-delete', [SinglesMatchController::class, 'forceDelete'])->name('matches.forceDelete');
    Route::get('/matches/filtered-players', [SinglesMatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');
 
    Route::get('/singles-matches/{id}', [SinglesMatchController::class, 'show'])->name('singles.matches.show');
    Route::get('/doubles-matches/{id}', [DoublesMatchController::class, 'show'])->name('doubles.matches.show');    
    // --------------------------
    // SINGLES MATCHES
    // -------------------------
    Route::prefix('matches/singles')->group(function () {
        Route::get('/', [SinglesMatchController::class, 'index'])->name('matches.singles.index');
        Route::get('/create', [SinglesMatchController::class, 'create'])->name('matches.singles.create');
        Route::post('/store', [SinglesMatchController::class, 'store'])->name('matches.singles.store');
        Route::get('/filtered-players', [SinglesMatchController::class, 'filteredPlayers'])->name('matches.singles.filteredPlayers');

        // **Single Edit Page for Inline Editing**
        Route::get('/edit', [SinglesMatchController::class, 'edit'])->name('matches.singles.edit');

        // **Edit Specific Match (if needed)**
        Route::get('/{match}/edit', [SinglesMatchController::class, 'editMatch'])->name('matches.singles.editMatch');

        Route::put('/{match}/update', [SinglesMatchController::class, 'update'])->name('matches.singles.update');
        Route::delete('/{match}/delete', [SinglesMatchController::class, 'delete'])->name('matches.singles.delete');

        // Locking and Unlocking Singles Tournaments
        Route::post('/lock', [SinglesMatchController::class, 'lockTournament'])->name('matches.singles.lockTournament');
        Route::post('/unlock', [SinglesMatchController::class, 'unlockTournament'])->name('matches.singles.unlockTournament');

        Route::get('/matches/singles/filtered-players', [SinglesMatchController::class, 'filteredPlayers'])->name('matches.singles.filteredPlayers');
    // Route to view a specific singles match
        Route::get('/matches/singles/{id}', [SinglesMatchController::class, 'show'])->name('matches.singles.show');
        route::get('matches/singles/{id}', [\App\Http\Controllers\SinglesMatchController::class, 'show'])
    ->name('matches.singles.show');

    
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
    // Route to view a specific doubles match
        Route::get('/matches/doubles/{id}', [DoublesMatchController::class, 'show'])->name('matches.doubles.show');
        Route::put('/matches/doubles/{matchId}/update', [DoublesMatchController::class, 'update'])->name('matches.doubles.update');

    route::get('matches/doubles/{id}', [\App\Http\Controllers\DoublesMatchController::class, 'show'])
    ->name('matches.doubles.show');    
    });

    // --------------------------
    // CATEGORIES
    // --------------------------
    Route::resource('categories', CategoryController::class)->except(['show']);

    // --------------------------
    // PROFILE SETTINGS (EDIT USER DETAILS)
    // --------------------------
    Route::get('/profile', [UserController::class, 'editProfile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
});

// --------------------------------------------------
// RESULTS
// --------------------------------------------------
Route::prefix('results')->group(function () {
 
});

// --------------------------------------------------
// PASSWORD RESET (FORGOT PASSWORD)
// --------------------------------------------------

// Show the form to request a password reset link.
// If the user is logged in, your controller can redirect them to the profile page.
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

// Process the request and send the password reset link email.
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Display the password reset form using the token.
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// Process the password update.
Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Optional: Redirect GET requests to /reset-password (without a token) back to the forgot password form.
Route::get('reset-password', function () {
    return redirect()->route('password.request');
});




     Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
  
     Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
     
    
    //payments
    

     // Player Routes
     Route::get('/payments/{tournament_id}/{category_id}', [PaymentController::class, 'showPaymentPage'])->name('payments.pay');
     Route::post('/payments/store', [PaymentController::class, 'storePayment'])->name('payments.store');
     
     // Admin Routes
     Route::get('/admin/payments', [PaymentController::class, 'adminViewPayments'])->name('admin.payments');
     Route::put('/admin/payments/{id}', [PaymentController::class, 'updatePaymentStatus'])->name('admin.payments.update');
     
     Route::post('/tournament/{tournament_id}/add-player', [TournamentController::class, 'addPlayerToTournament'])
     ->name('tournament.add-player')
     ->middleware('auth');
     
     Route::get('/tournament/{tournament_id}/add-players', [TournamentController::class, 'showPlayerSelection'])
     ->name('tournament.show-player-selection')
     ->middleware('auth');
  
 