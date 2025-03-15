<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard route die doorverwijst naar het juiste dashboard.
Route::get('dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Include team routes
require __DIR__ . '/teams.php';

// Include area routes
require __DIR__ . '/areas.php';

// Include authentication routes
require __DIR__ . '/auth.php';
