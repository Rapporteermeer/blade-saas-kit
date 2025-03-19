<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamType;
use App\Models\Role;
use App\Models\Invitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        $teamTypes = TeamType::all();
        return view('auth.register', compact('teamTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Check if there's a pending invitation
        $invitationToken = session('invitation_token');
        $invitedEmail = session('invited_email');

        // Validate common fields
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // If invited, ensure email matches invitation
        if ($invitedEmail) {
            $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class, 'in:' . $invitedEmail];
        }

        // Add team fields validation only if not invited
        if (!$invitationToken) {
            $rules['team_name'] = ['required', 'string', 'max:255'];
            $rules['team_type_id'] = ['required', 'exists:team_types,id'];
        }

        $validated = $request->validate($rules);
        $validated['password'] = Hash::make($validated['password']);

        // Create user
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        // If not invited, create a new team
        if (!$invitationToken) {
            $team = Team::create([
                'name'         => $validated['team_name'],
                'team_type_id' => $validated['team_type_id'],
                'owner_id'     => $user->id,
            ]);

            // Assign owner role to the user in this team
            $ownerRole = Role::where('name', 'Owner')->first();
            $team->users()->attach($user->id, ['role_id' => $ownerRole->id]);

            // Set as current team
            $user->current_team_id = $team->id;
            $user->save();

            // Start a 30-day trial for the team owner
            $user->createAsStripeCustomer();
            $user->trial_ends_at = now()->addDays(30);
            $user->save();

            Auth::login($user);

            // Redirect to dashboard which will handle redirection to the appropriate area
            return redirect(route('dashboard', absolute: false));
        } else {
            // Handle invitation
            Auth::login($user);

            // Clear the invited email from session
            session()->forget('invited_email');

            // Redirect to invitation acceptance
            $token = session()->pull('invitation_token');
            return redirect()->route('invitations.accept', $token);
        }
    }
}
