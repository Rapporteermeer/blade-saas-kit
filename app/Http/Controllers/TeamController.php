<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams;
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        // Use Gate facade for Laravel 12
        if (Gate::denies('create', Team::class)) {
            abort(403, 'Only team owners can create new teams.');
        }

        $teamTypes = TeamType::all();
        return view('teams.create', compact('teamTypes'));
    }

    public function store(Request $request)
    {
        // Use Gate facade for Laravel 12
        if (Gate::denies('create', Team::class)) {
            abort(403, 'Only team owners can create new teams.');
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'team_type_id' => 'required|exists:team_types,id',
        ]);

        $team = Team::create([
            'name'         => $validated['name'],
            'team_type_id' => $validated['team_type_id'],
            'owner_id'     => auth()->id(),
        ]);

        // Assign owner role to the user in this team
        $ownerRole = Role::where('name', 'Owner')->first();
        $team->users()->attach(auth()->id(), ['role_id' => $ownerRole->id]);

        // Start trial for the team owner if they don't already have one
        $user = auth()->user();
        if (!$user->onTrial() && !$user->subscribed()) {
            // Create a generic trial that isn't tied to a specific plan yet
            $user->createAsStripeCustomer();
            $user->trial_ends_at = now()->addDays(30);
            $user->save();
        }

        // Set as current team
        auth()->user()->switchTeam($team);

        return redirect()->route('teams.show', $team)->with('success', 'Team created successfully! You have a 30-day free trial.');
    }


    public function show(Team $team)
    {
        Gate::authorize('view', $team);

        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        Gate::authorize('update', $team);

        $teamTypes = TeamType::all();
        return view('teams.edit', compact('team', 'teamTypes'));
    }

    public function update(Request $request, Team $team)
    {
        Gate::authorize('update', $team);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'team_type_id' => 'required|exists:team_types,id',
        ]);

        $team->update($validated);

        return redirect()->route('teams.show', $team)->with('success', 'Team updated successfully!');
    }

    public function destroy(Team $team)
    {
        Gate::authorize('delete', $team);

        // Check if this is the user's current team
        $user = Auth::user();
        if ($user->current_team_id === $team->id) {
            // Find another team to switch to
            $newTeam = $user->teams()->where('id', '!=', $team->id)->first();
            if ($newTeam) {
                $user->switchTeam($newTeam);
            } else {
                $user->current_team_id = null;
                $user->save();
            }
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }

    public function switchTeam(Team $team)
    {
        Gate::authorize('view', $team);

        // Verify the user is a member of this team
        if (!Auth::user()->teams->contains($team->id)) {
            return redirect()->route('teams.index')
                ->with('error', 'You are not a member of this team.');
        }

        Auth::user()->switchTeam($team);

        return redirect()->route('dashboard')
            ->with('success', 'Switched to team: ' . $team->name);
    }
}
