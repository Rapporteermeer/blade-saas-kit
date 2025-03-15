<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamType;

class DashboardController extends Controller
{
    // Deze controller is verantwoordelijk voor het doorverwijzen van gebruikers naar het juiste dashboard
    // op basis van het type team waartoe ze behoren.

    public function redirect()
    {
        $user = auth()->user();

        // Als de gebruiker geen huidig team heeft, stuur ze naar de teams pagina
        if (!$user->current_team_id) {
            return redirect()->route('teams.index')
                ->with('info', 'Please select or create a team first.');
        }

        $currentTeam = $user->currentTeam;
        $teamType = $currentTeam->teamType;

        // Stuur de gebruiker door naar het juiste dashboard op basis van het team type
        switch ($teamType->name) {
            case 'Home Care':
                return redirect()->route('areas.home-care.index');
            case 'Housing Assistance':
                return redirect()->route('areas.housing-assistance.index');
            case 'Outpatient Guidance':
                return redirect()->route('areas.outpatient-guidance.index');
            default:
                // Fallback voor eventuele nieuwe team types
                return redirect()->route('teams.index');
        }
    }

}
