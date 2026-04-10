<?php

namespace App\Livewire;

use App\Rules\SafeUrl;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEditor extends Component
{
    use WithFileUploads;

    public string $name         = '';
    public string $email        = '';
    public string $title        = '';
    public string $bio          = '';
    public string $phone        = '';
    public string $linkedin_url = '';
    public $avatar = null;
    public bool $saved = false;

    public string $current_password      = '';
    public string $password              = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $u = auth()->user();
        $this->name         = $u->name;
        $this->email        = $u->email;
        $this->title        = $u->title        ?? '';
        $this->bio          = $u->bio          ?? '';
        $this->phone        = $u->phone        ?? '';
        $this->linkedin_url = $u->linkedin_url ?? '';
    }

    public function save(FileUploadService $uploader): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'title'        => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'linkedin_url' => ['nullable', 'url', 'max:500', new SafeUrl()],
            'avatar'       => ['nullable', 'file', 'max:5120'],
        ]);

        if ($this->avatar) {
            try {
                $old = $user->avatar;
                $validated['avatar'] = $uploader->storeImage($this->avatar, 'avatars', 'uploads');
                if ($old) $uploader->delete($old, 'uploads');
                $this->avatar = null;
            } catch (ValidationException $e) {
                $this->addError('avatar', collect($e->errors())->first()[0] ?? 'Upload failed.');
                return;
            }
        } else {
            unset($validated['avatar']);
        }

        $user->fill($validated)->save();

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        }

        $this->saved = true;
        $this->js("window.toast('success', 'Profile updated successfully.', 'Saved')");
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update(['password' => Hash::make($this->password)]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->js("window.toast('success', 'Password changed successfully.', 'Done')");
    }

    public function render()
    {
        return view('livewire.profile-editor', ['user' => auth()->user()]);
    }
}
