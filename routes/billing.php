<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Billing index page
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');

    // Stripe Checkout for new subscriptions
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');

    // Stripe Customer Portal for managing subscriptions
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
});