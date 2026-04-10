<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberInvitationMail;
use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminInvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with(['organization', 'invitedBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $organizations = Organization::active()->select(['id', 'name', 'slug'])->orderBy('name')->get();

        return view('admin.invitations.index', compact('invitations', 'organizations'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email'           => 'required|email|unique:users,email',
            'role'            => 'required|in:member,friend,secretariat',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Expire previous pending invitations for this email
        Invitation::where('email', $request->email)
            ->whereNull('accepted_at')
            ->update(['expires_at' => now()]);

        $invitation = Invitation::generate(
            email: $request->email,
            role: $request->role,
            organizationId: $request->organization_id,
            invitedBy: auth()->id(),
        );

        $mailError = null;
        try {
            Mail::to($request->email)->send(new MemberInvitationMail($invitation));
        } catch (\Exception $e) {
            report($e);
            $mailError = $e->getMessage();
        }

        activity()->causedBy(auth()->user())
            ->log("Sent invitation to {$request->email}");

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => "Invitation sent to {$request->email}.",
                'mail_error' => $mailError,
                'invitation' => [
                    'id'           => $invitation->id,
                    'email'        => $invitation->email,
                    'role'         => $invitation->role,
                    'organization' => $invitation->organization?->name,
                    'expires_at'   => $invitation->expires_at->format('M j, Y'),
                    'expires_diff' => $invitation->expires_at->diffForHumans(),
                    'created_at'   => $invitation->created_at->format('M j, Y'),
                ],
            ]);
        }

        return back()->with('success', "Invitation sent to {$request->email}.");
    }

    public function destroy(Request $request, Invitation $invitation)
    {
        $invitation->update(['expires_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Invitation revoked.']);
        }

        return back()->with('success', 'Invitation revoked.');
    }
}
