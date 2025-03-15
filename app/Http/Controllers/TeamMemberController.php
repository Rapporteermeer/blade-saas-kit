<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamMemberController extends Controller
{
    public function index(Team $team)
    {
        Gate::authorize('viewMembers', $team);

        $members = $team->users()->with('roles')->get();
        return view('teams.members.index', compact('team', 'members'));
    }

    public function edit(Team $team, User $user)
    {
        Gate::authorize('updateMembers', $team);

        // Check if the user is a member of this team
        $teamUser = $team->users()->where('user_id', $user->id)->first();
        if (!$teamUser) {
            return redirect()->route('teams.members.index', $team)
                ->with('error', 'This user is not a member of this team.');
        }

        $roles = Role::whereIn('name', ['Employer', 'Employee'])->get();
        $currentRole = $teamUser->pivot->role_id;

        return view('teams.members.edit', compact('team', 'user', 'roles', 'currentRole'));
    }

    public function update(Request $request, Team $team, User $user)
    {
        Gate::authorize('updateMembers', $team);

        // Check if the user is a member of this team
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('teams.members.index', $team)
                ->with('error', 'This user is not a member of this team.');
        }

        // Prevent changing the role of the team owner
        if ($team->owner_id === $user->id) {
            return redirect()->route('teams.members.index', $team)
                ->with('error', 'You cannot change the role of the team owner.');
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $team->users()->updateExistingPivot($user->id, ['role_id' => $validated['role_id']]);

        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Member role updated successfully!');
    }

    public function destroy(Team $team, User $user)
    {
        Gate::authorize('removeMembers', $team);

        // Prevent removing the owner
        if ($team->owner_id === $user->id) {
            return back()->withErrors(['error' => 'You cannot remove the team owner.']);
        }

        // Check if the user is a member of this team
        if (!$team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('teams.members.index', $team)
                ->with('error', 'This user is not a member of this team.');
        }

        // Check if the user is removing themselves
        if (Auth::id() === $user->id) {
            // If this is their current team, switch to another team
            if (Auth::user()->current_team_id === $team->id) {
                $newTeam = Auth::user()->teams()->where('id', '!=', $team->id)->first();
                if ($newTeam) {
                    Auth::user()->switchTeam($newTeam);
                } else {
                    Auth::user()->current_team_id = null;
                    Auth::user()->save();
                }
            }
        }

        $team->users()->detach($user->id);

        return redirect()->route('teams.members.index', $team)
            ->with('success', 'Member removed from team successfully!');
    }
}
