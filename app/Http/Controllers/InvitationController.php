<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\Role;
use App\Models\User;
use App\Mail\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class InvitationController extends Controller
{
    public function create(Team $team)
    {
        if (Gate::denies('invite', $team)) {
            abort(403);
        }

        $roles = Role::whereIn('name', ['Employer', 'Employee'])->get();
        return view('invitations.create', compact('team', 'roles'));
    }

    public function store(Request $request, Team $team)
    {
        if (Gate::denies('invite', $team)) {
            abort(403);
        }

        $validated = $request->validate([
            'email'   => 'required|email',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser && $team->users()->where('user_id', $existingUser->id)->exists()) {
            return back()->withErrors(['email' => 'This user is already a member of this team.']);
        }

        // Create invitation
        $invitation = Invitation::create([
            'team_id'    => $team->id,
            'email'      => $validated['email'],
            'role_id'    => $validated['role_id'],
            'token'      => Str::random(64),
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Send invitation email
        Mail::to($validated['email'])->send(new TeamInvitation($invitation));

        return redirect()->route('teams.show', $team)->with('success', 'Invitation sent successfully!');
    }


    // Verwerkt het accepteren van een uitnodiging.
    public function accept($token)
    {
        // Zoek de uitnodiging op basis van token.
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Als gebruiker is ingelogd:
        if (auth()->check()) {
            // Voeg gebruiker toe aan team
            if ($invitation->team->users()->where('user_id', auth()->id())->exists()) {
                $invitation->delete();
                return redirect()->route('teams.show', $invitation->team)
                    ->with('info', 'You are already a member of this team.');
            }

            // Voeg gebruiker toe aan team.
            $invitation->team->users()->attach(auth()->id(), ['role_id' => $invitation->role_id]);

            // Stel dit in als huidig team in.
            auth()->user()->switchTeam($invitation->team);

            // Verwijder uitnodiging.
            $invitation->delete();

            // Stuur gebruiker door naar dashboard met succesmelding.
            return redirect()->route('dashboard')
                ->with('success', 'You have joined the team!');
        }
        // Anders:
        else {
            // Sla uitnodigingstoken op in sessie
            session(['invitation_token' => $token]);
            // Sla uitgenodigde email op in sessie
            session(['invited_email' => $invitation->email]);

            // Stuur gebruiker door naar registratiepagina met info-melding.
            return redirect()->route('register')
                ->with('info', 'Please register or login to accept the invitation.');
        }
    }
}
