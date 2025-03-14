<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function view(User $user, Team $team)
    {
        return $user->teams->contains($team->id);
    }
    public function create(User $user)
    {
        // Only allow users with the Owner role to create teams
        // For new users without any teams, we'll still allow them to create their first team
        if ($user->teams()->count() === 0) {
            return true; // Allow new users to create their first team
        }

        // Check if the user has the Owner role in any of their teams
        foreach ($user->teams as $team) {
            $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
            $roleName = \App\Models\Role::find($role)->name;

            if ($roleName === 'Owner') {
                return true;
            }
        }

        return false; // User is not an owner of any team
    }

    public function update(User $user, Team $team)
    {
        return $user->id === $team->owner_id;
    }

    public function delete(User $user, Team $team)
    {
        return $user->id === $team->owner_id;
    }

    public function invite(User $user, Team $team)
    {
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }

    public function viewMembers(User $user, Team $team)
    {
        return $user->teams->contains($team->id);
    }

    public function updateMembers(User $user, Team $team)
    {
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }

    public function removeMembers(User $user, Team $team)
    {
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }
}
