<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamType;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = auth()->user();

        // If user has no current team, redirect to teams page
        if (!$user->current_team_id) {
            return redirect()->route('teams.index')
                ->with('info', 'Please select or create a team first.');
        }

        $currentTeam = $user->currentTeam;
        $teamType = $currentTeam->teamType;

        // Redirect based on team type
        switch ($teamType->name) {
            case 'Home Care':
                return redirect()->route('areas.home-care.index');
            case 'Housing Assistance':
                return redirect()->route('areas.housing-assistance.index');
            case 'Outpatient Guidance':
                return redirect()->route('areas.outpatient-guidance.index');
            default:
                // Fallback for any new team types
                return redirect()->route('teams.index');
        }
    }
}
