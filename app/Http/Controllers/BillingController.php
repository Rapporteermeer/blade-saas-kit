<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

class BillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('billing.index', [
            'onTrial'     => $user->onTrial(),
            'trialEndsAt' => $user->trial_ends_at,
            'subscribed'  => $user->subscribed(),
        ]);
    }

    public function portal(Request $request)
    {
        $user = $request->user();

        // If user is not a Stripe customer yet, create them
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        // Redirect to the billing portal
        return $user->redirectToBillingPortal(route('billing.index'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $priceId = $request->input('price_id', env('STRIPE_PRICE_MONTHLY_ID'));

        try {
            // Create a checkout session for the user
            return $user->newSubscription('default', $priceId)
                ->allowPromotionCodes()
                ->trialUntil($user->trial_ends_at) // Continue any existing trial
                ->checkout([
                    'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url'  => route('billing.cancel'),
                ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        return view('billing.success');
    }

    public function cancel(Request $request)
    {
        return view('billing.cancel');
    }
}
