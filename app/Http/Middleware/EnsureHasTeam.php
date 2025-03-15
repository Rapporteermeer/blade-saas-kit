<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasTeam
{
    // Deze middleware zorgt ervoor dat een ingelogde gebruiker altijd een huidig team heeft
    // Als er geen huidig team is ingesteld maar de gebruiker wel teams heeft,
    // wordt automatisch het eerste team geselecteerd

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->currentTeam && auth()->user()->teams->count() > 0) {
            // Selecteer automatisch het eerste team als er geen huidig team is ingesteld
            auth()->user()->switchTeam(auth()->user()->teams->first());
        }

        return $next($request);
    }
}