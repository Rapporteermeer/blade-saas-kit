<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip if user is not authenticated
        if (!$user) {
            return $next($request);
        }

        // Skip check if already on the billing page to prevent redirect loops
        if ($request->routeIs('billing.portal')) {
            return $next($request);
        }

        // Skip the check if the user doesn't need a subscription
        if (!$user->needsSubscription()) {
            return $next($request);
        }

        // If user needs subscription but doesn't have one and isn't on trial
        if (!$user->hasValidSubscriptionOrTrial()) {
            return redirect()->route('billing.portal')
                ->with('warning', 'Your trial has expired. Please subscribe to continue using the platform.');
        }

        return $next($request);
    }
}
