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
        // SuperAdmins mogen alles.
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function view(User $user, Team $team)
    {
        // Gebruiker mag team bekijken als ze lid zijn
        return $user->teams->contains($team->id);
    }

    public function create(User $user)
    {
        // Nieuwe gebruikers mogen hun eerste team aanmaken.
        if ($user->teams()->count() === 0) {
            return true;
        }

        // Daarna alleen als ze Owner zijn van een bestaand team.
        foreach ($user->teams as $team) {
            $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
            $roleName = \App\Models\Role::find($role)->name;

            if ($roleName === 'Owner') {
                return true;
            }
        }

        // Anders mag het team niet aangemaakt worden.
        return false;
    }

    public function update(User $user, Team $team)
    {
        // Alleen de eigenaar mag het team bijwerken.
        return $user->id === $team->owner_id;
    }

    public function delete(User $user, Team $team)
    {
        // Alleen de eigenaar mag het team verwijderen.
        return $user->id === $team->owner_id;
    }

    public function invite(User $user, Team $team)
    {
        // Alleen de eigenaar en de Employer mogen leden uitnodigen.
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }


    // Methoden voor het beheren van teamleden


    public function viewMembers(User $user, Team $team)
    {
        // Alle teamleden mogen andere leden bekijken.
        return $user->teams->contains($team->id);
    }

    public function updateMembers(User $user, Team $team)
    {
        // Alleen de eigenaar en de Employer mogen teamleden bijwerken.
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }

    public function removeMembers(User $user, Team $team)
    {
        // Alleen de eigenaar en de Employer mogen teamleden verwijderen.
        $role = $team->users()->where('user_id', $user->id)->first()->pivot->role_id;
        $roleName = \App\Models\Role::find($role)->name;

        return $user->id === $team->owner_id || in_array($roleName, ['Owner', 'Employer']);
    }
}
