<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamTypeMatches
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $teamType): Response
    {
        $user = $request->user();

        // If user has no current team, redirect to teams page
        if (!$user->current_team_id) {
            return redirect()->route('teams.index')
                ->with('info', 'Please select or create a team first.');
        }

        $currentTeam = $user->currentTeam;

        // Check if the current team's type matches the required type
        if ($currentTeam->teamType->name !== $teamType) {
            // If user has a team of the required type, switch to it
            foreach ($user->teams as $team) {
                if ($team->teamType->name === $teamType) {
                    $user->switchTeam($team);
                    return redirect()->to($request->url());
                }
            }

            // Otherwise, redirect to dashboard which will handle redirection
            return redirect()->route('dashboard')
                ->with('error', "You don't have access to this area. It's for {$teamType} teams only.");
        }

        return $next($request);
    }
}
