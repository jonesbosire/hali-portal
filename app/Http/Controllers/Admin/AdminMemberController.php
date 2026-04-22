<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountStatusChanged;
use Illuminate\Http\Request;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('organizations')
            ->orderByDesc('created_at');

        if ($search = $request->get('q')) {
            // Escape LIKE special characters so % and _ in the search term are
            // treated as literals, not wildcards, preventing unexpected broad matches.
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);
            $query->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%{$escaped}%")
                  ->orWhere('email', 'like', "%{$escaped}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.members.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('organizations', 'eventRegistrations.event', 'posts', 'opportunities');
        return view('admin.members.show', compact('user'));
    }

    /**
     * Permanently delete a member (super_admin only — gated in routes).
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['deleted_user' => $user->email])
            ->log('user_deleted');

        $user->delete();

        return redirect()->route('admin.members.index')->with('success', "Member {$user->name} has been deleted.");
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,pending',
        ]);

        // Prevent demoting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $user->update(['status' => $request->status]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['new_status' => $request->status])
            ->log('user_status_changed');

        // Notify the affected user (queued — non-blocking)
        $user->notify(new AccountStatusChanged($request->status));

        return back()->with('success', "User status updated to {$request->status}.");
    }
}
