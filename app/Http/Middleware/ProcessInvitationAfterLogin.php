<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Invitation;

class ProcessInvitationAfterLogin
{
    // Deze middleware verwerkt uitnodigingen nadat een gebruiker is ingelogd
    // Als er een uitnodigingstoken in de sessie staat, wordt deze verwerkt
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Controleer of gebruiker is ingelogd en een uitnodigingstoken in sessie heeft.
        if (auth()->check() && session()->has('invitation_token')) {
            $token = session()->pull('invitation_token');

            // Zoek de uitnodiging
            $invitation = Invitation::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if ($invitation) {
                // Controleer of gebruiker al lid is van het team
                if ($invitation->team->users()->where('user_id', auth()->id())->exists()) {
                    $invitation->delete();
                    return redirect()->route('teams.show', $invitation->team)
                        ->with('info', 'You are already a member of this team.');
                }

                // Zo niet, voeg gebruiker toe aan team.
                $invitation->team->users()->attach(auth()->id(), ['role_id' => $invitation->role_id]);

                // Stel dit in als huidig team in.
                auth()->user()->switchTeam($invitation->team);

                // Verwijder uitnodiging.
                $invitation->delete();

                // Verwijder de uitgenodigde email uit de sessie.
                session()->forget('invited_email');

                // Stuur gebruiker door naar dashboard met succesmelding.
                return redirect()->route('dashboard')
                    ->with('success', 'You have joined the team!');
            }
        }

        return $response;
    }
}
