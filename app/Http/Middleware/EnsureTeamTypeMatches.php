<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamTypeMatches
{
    // Deze middleware controleert of het huidige team van de gebruiker overeenkomt
    // met het vereiste team type voor een bepaalde route.

    public function handle(Request $request, Closure $next, string $teamType): Response
    {
        $user = $request->user();

        // Als gebruiker geen huidig team heeft, stuur naar teams pagina.
        if (!$user->current_team_id) {
            return redirect()->route('teams.index')
                ->with('info', 'Please select or create a team first.');
        }

        $currentTeam = $user->currentTeam;

        // Controleer of het huidige team type overeenkomt met het vereiste type.
        if ($currentTeam->teamType->name !== $teamType) {
            // Als de gebruiker een team heeft van het vereiste type, schakel daarnaar.
            foreach ($user->teams as $team) {
                if ($team->teamType->name === $teamType) {
                    $user->switchTeam($team);
                    return redirect()->to($request->url());
                }
            }

            // Anders, stuur terug naar dashboard met foutmelding.
            return redirect()->route('dashboard')
                ->with('error', "You don't have access to this area. It's for {$teamType} teams only.");
        }

        return $next($request);
    }
}
