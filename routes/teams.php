<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Middleware\EnsureHasTeam;
use Illuminate\Support\Facades\Route;


/*
| Routes voor teams en teamleden.
| Alle routes zijn beschermd met middleware die ervoor zorgt dat de gebruiker een team heeft.
*/


// Team routes
Route::middleware(['auth', EnsureHasTeam::class])->group(function () {
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

// Invitation accept route.
Route::get('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
