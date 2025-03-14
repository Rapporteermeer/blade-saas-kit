<?php

use App\Http\Controllers\Area\HomeCareController;
use App\Http\Controllers\Area\HousingAssistanceController;
use App\Http\Controllers\Area\OutpatientGuidanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;

use App\Http\Middleware\EnsureHasTeam;
use App\Http\Middleware\EnsureTeamTypeMatches;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect to dashboard based on team type
Route::get('dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Home Care routes.
Route::middleware(['auth', 'verified', EnsureTeamTypeMatches::class . ':Home Care'])->group(function () {
    Route::get('home-care/dashboard', [HomeCareController::class, 'index'])->name('areas.home-care.index');
    // Add other Home Care specific routes here
});

// Housing Assistance routes.
Route::middleware(['auth', 'verified', EnsureTeamTypeMatches::class . ':Housing Assistance'])->group(function () {
    Route::get('housing-assistance/dashboard', [HousingAssistanceController::class, 'index'])->name('areas.housing-assistance.index');
    // Add other Housing Assistance specific routes here
});

// Outpatient Guidance routes.
Route::middleware(['auth', 'verified', EnsureTeamTypeMatches::class . ':Outpatient Guidance'])->group(function () {
    Route::get('outpatient-guidance/dashboard', [OutpatientGuidanceController::class, 'index'])->name('areas.outpatient-guidance.index');
    // Add other Outpatient Guidance specific routes here
});


Route::middleware(['auth', EnsureHasTeam::class])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

Route::middleware(['auth', EnsureHasTeam::class])->group(function () {
    // Team routes

    // Teams
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::post('/teams/{team}/switch', [TeamController::class, 'switchTeam'])->name('teams.switch');

    // Team Members
    Route::get('/teams/{team}/members', [TeamMemberController::class, 'index'])->name('teams.members.index');
    Route::get('/teams/{team}/members/{user}/edit', [TeamMemberController::class, 'edit'])->name('teams.members.edit');
    Route::put('/teams/{team}/members/{user}', [TeamMemberController::class, 'update'])->name('teams.members.update');
    Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');

    // Invitations
    Route::get('/teams/{team}/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/teams/{team}/invitations', [InvitationController::class, 'store'])->name('invitations.store');
});

// Public invitation acceptance route
Route::get('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

require __DIR__ . '/auth.php';
