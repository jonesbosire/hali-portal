<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Rules\SafeUrl;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    /**
     * Update profile info (name, email, bio, phone, linkedin, avatar).
     * Password is handled separately in updatePassword().
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'title'        => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'linkedin_url' => ['nullable', 'url', 'max:500', new SafeUrl()],
            'avatar'       => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('avatar')) {
            try {
                $oldAvatar = $user->avatar;
                $validated['avatar'] = $this->uploader->storeImage(
                    $request->file('avatar'),
                    'avatars',
                    'uploads'
                );
                // Delete old avatar after successful upload
                if ($oldAvatar) {
                    $this->uploader->delete($oldAvatar, 'uploads');
                }
            } catch (ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
        } else {
            unset($validated['avatar']); // don't overwrite existing avatar
        }

        $user->fill($validated)->save();

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password — separate route/form for clarity and security.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Invalidate every other active session (other browsers/devices) so
        // a stolen session token can't be used after a password change.
        Auth::logoutOtherDevices($request->password);

        // Regenerate the current session token to prevent fixation
        $request->session()->regenerateToken();

        return back()->with('success', 'Password changed successfully. All other devices have been signed out.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        auth()->logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function organization(Request $request)
    {
        $user         = $request->user();
        $organization = $user->primaryOrganization();
        $teamMembers  = $organization?->members()->get() ?? collect();

        return view('profile.organization', compact('user', 'organization', 'teamMembers'));
    }

    public function updateOrganization(Request $request)
    {
        $user         = $request->user();
        $organization = $user->primaryOrganization();

        if (!$organization) {
            return back()->with('error', 'No organization found.');
        }

        // Only org admin, primary contact, or portal admin can edit
        $pivot = $user->organizations()
            ->where('organization_id', $organization->id)
            ->first()?->pivot;

        if (!$pivot || (!$pivot->is_primary && $pivot->role !== 'admin' && !$user->isAdmin())) {
            abort(403, 'You do not have permission to edit this organization.');
        }

        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'website_url'        => ['nullable', 'url', 'max:500', new SafeUrl()],
            'country'            => ['nullable', 'string', 'max:100'],
            'region'             => ['nullable', 'string', 'max:100'],
            'founding_year'      => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'students_supported' => ['nullable', 'integer', 'min:0'],
            'logo'               => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('logo')) {
            try {
                $oldLogo = $organization->logo_path;
                $validated['logo_path'] = $this->uploader->storeImage(
                    $request->file('logo'),
                    'logos',
                    'uploads'
                );
                unset($validated['logo']);
                if ($oldLogo) {
                    $this->uploader->delete($oldLogo, 'uploads');
                }
            } catch (ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
        } else {
            unset($validated['logo']);
        }

        $organization->update($validated);

        $listing = $organization->directoryListing ?? $organization->directoryListing()->create([
            'user_id' => $user->id,
        ]);

        $listing->update([
            'listing_title' => $organization->name,
            'last_updated_at' => now(),
        ]);

        return back()->with('success', 'Organization updated successfully.');
    }

}
