<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class InvitationController extends Controller
{
    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('auth.invitation-expired', compact('invitation'));
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')->with('status', 'Invitation already used. Please log in.');
        }

        return view('auth.accept-invitation', compact('invitation', 'token'));
    }

    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            return redirect()->route('login')->withErrors(['token' => 'This invitation is no longer valid.']);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'title'      => ['nullable', 'string', 'max:255'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'               => trim($request->first_name . ' ' . $request->last_name),
            'email'              => $invitation->email,
            'password'           => Hash::make($request->password),
            'role'               => $invitation->role,
            'status'             => 'active',
            'email_verified_at'  => now(),
            'title'              => $request->title,
            'membership_tier_id' => $invitation->membership_tier_id,
            'dues_due_date'      => $invitation->membership_tier_id ? now()->addYear()->toDateString() : null,
        ]);

        // Attach to organization if specified
        if ($invitation->organization_id) {
            $user->organizations()->attach($invitation->organization_id, [
                'role' => 'staff',
                'is_primary' => false,
                'joined_at' => now(),
            ]);

            // Create directory listing if org doesn't have one
            $org = Organization::find($invitation->organization_id);
            if ($org && !$org->directoryListing()->exists()) {
                $org->directoryListing()->create([
                    'user_id' => $user->id,
                    'listing_title' => $org->name,
                ]);
            }
        }

        $invitation->update(['accepted_at' => now()]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to the HALI Access Partner Portal!');
    }
}
