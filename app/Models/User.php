<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }


    // Teams gerelateerd


    public function teams()
    {
        // Een gebruiker kan meerdere teams hebben en heeft een rol in elk team.
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function ownedTeams()
    {
        // Teams waarvan de gebruiker eigenaar is.
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function currentTeam()
    {
        // Het huidige actieve team van de gebruiker.
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function switchTeam($team)
    {
        // Schakelt naar een ander team.
        $this->current_team_id = $team->id;
        $this->save();

        // Stelt het nieuwe team in als huidig team
        $teamUser = $team->users()->where('user_id', $this->id)->first();
        if ($teamUser) {
            $roleId = $teamUser->pivot->role_id;

            // Synchroniseert de rollen van de gebruiker met hun rol in dit team.
            // Eerst, verwijder alle bestaande rollen.
            $this->roles()->detach();

            // Voeg de rol toe die de gebruiker heeft in dit team.
            $this->roles()->attach($roleId);
        }

        return $this;
    }



    // Rollen gerelateerd

    public function roles()
    {
        // Een gebruiker kan meerdere rollen hebben.
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($roleName)
    {
        // Controleert of de gebruiker een bepaalde rol heeft.
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function isSuperAdmin()
    {
        // Controleert of de gebruiker de rol "SuperAdmin" heeft.
        return $this->hasRole('SuperAdmin');
    }

}
