<?php

use App\Http\Controllers\Area\HomeCareController;
use App\Http\Controllers\Area\HousingAssistanceController;
use App\Http\Controllers\Area\OutpatientGuidanceController;
use App\Http\Middleware\EnsureTeamTypeMatches;
use App\Http\Middleware\EnsureUserHasSubscription;
use Illuminate\Support\Facades\Route;


/*
| Routes voor verschillende team types.
| Elke set routes is beschermd met middleware die controleert of het team type overeenkomt
*/


// Home Care routes.
Route::middleware(['auth', 'verified', EnsureUserHasSubscription::class, EnsureTeamTypeMatches::class . ':Home Care'])->group(function () {
    Route::get('home-care/dashboard', [HomeCareController::class, 'index'])->name('areas.home-care.index');
    // Add other Home Care specific routes here
});

Route::middleware(['auth', 'verified', EnsureUserHasSubscription::class, EnsureTeamTypeMatches::class . ':Housing Assistance'])->group(function () {
    Route::get('housing-assistance/dashboard', [HousingAssistanceController::class, 'index'])->name('areas.housing-assistance.index');
    // Add other Housing Assistance specific routes here
});

Route::middleware(['auth', 'verified', EnsureUserHasSubscription::class, EnsureTeamTypeMatches::class . ':Outpatient Guidance'])->group(function () {
    Route::get('outpatient-guidance/dashboard', [OutpatientGuidanceController::class, 'index'])->name('areas.outpatient-guidance.index');
    // Add other Outpatient Guidance specific routes here
});
