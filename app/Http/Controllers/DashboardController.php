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
        if ($user->needsSubscription() && !$user->hasValidSubscriptionOrTrial()) {
            return redirect()->route('billing.portal')
                ->with('warning', 'Your trial has expired. Please subscribe to continue using the platform.');
        }

        $team = $user->currentTeam;

        if (!$team) {
            return redirect()->route('teams.index')
                ->with('info', 'Please select or create a team first.');
        }

        // Stuur de gebruiker door naar het juiste dashboard op basis van het team type
        switch ($team->teamType->name) {
            case 'Home Care':
                return redirect()->route('areas.home-care.index');
            case 'Housing Assistance':
                return redirect()->route('areas.housing-assistance.index');
            case 'Outpatient Guidance':
                return redirect()->route('areas.outpatient-guidance.index');
            default:
                return redirect()->route('teams.index');
        }
    }

}
