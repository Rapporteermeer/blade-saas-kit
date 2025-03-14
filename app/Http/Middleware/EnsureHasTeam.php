<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasTeam
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->currentTeam && auth()->user()->teams->count() > 0) {
            // Auto-select the first team if no current team is set
            auth()->user()->switchTeam(auth()->user()->teams->first());
        }

        return $next($request);
    }
}