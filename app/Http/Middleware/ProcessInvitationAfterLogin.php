<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Invitation;

class ProcessInvitationAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if user is logged in and has an invitation token in session
        if (auth()->check() && session()->has('invitation_token')) {
            $token = session()->pull('invitation_token');

            // Find the invitation
            $invitation = Invitation::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if ($invitation) {
                // Check if user is already a member of the team
                if ($invitation->team->users()->where('user_id', auth()->id())->exists()) {
                    $invitation->delete();
                    return redirect()->route('teams.show', $invitation->team)
                        ->with('info', 'You are already a member of this team.');
                }

                // Add user to team
                $invitation->team->users()->attach(auth()->id(), ['role_id' => $invitation->role_id]);

                // Set this as the user's current team
                auth()->user()->switchTeam($invitation->team);

                // Delete invitation
                $invitation->delete();

                // Clear the invited email from session
                session()->forget('invited_email');

                // Redirect to dashboard
                return redirect()->route('dashboard')
                    ->with('success', 'You have joined the team!');
            }
        }

        return $response;
    }
}
