<div class="space-y-5 max-w-2xl">


    {{-- Avatar + personal info --}}
    <form wire:submit="save" wire:loading.class="opacity-60" class="space-y-5">

        {{-- Avatar --}}
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">
            <h2 class="text-sm font-semibold text-on-surface mb-4">Profile Photo</h2>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-16 h-16 rounded-full object-cover border-2 border-surface-container-high"
                         id="avatar-preview">
                    <div wire:loading wire:target="avatar"
                         class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="cursor-pointer text-sm bg-white border border-outline-variant text-on-surface px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors font-medium" id="avatar-label">
                        Change photo
                        <input type="file" wire:model="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden"
                               onchange="
                                   document.getElementById('avatar-preview').src = URL.createObjectURL(this.files[0]);
                                   document.getElementById('avatar-label').textContent = this.files[0].name.length > 20 ? this.files[0].name.substring(0,20)+'…' : this.files[0].name;
                                   document.getElementById('avatar-staged').classList.remove('hidden');
                               ">
                    </label>
                    <p class="text-xs text-on-surface-variant mt-1">JPG, PNG, GIF, WebP · max 5 MB</p>
                    <p id="avatar-staged" class="hidden mt-1 text-xs text-teal-600 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Photo selected — click Save to apply
                    </p>
                    @error('avatar') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Personal info --}}
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-on-surface">Personal Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Full Name *</label>
                    <input wire:model="name" type="text" required
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary/30 @error('name') border-error @enderror bg-white">
                    @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Job Title</label>
                    <input wire:model="title" type="text" placeholder="e.g. Admissions Counselor"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Email *</label>
                    <input wire:model="email" type="email" required
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('email') border-error @enderror bg-white">
                    @error('email') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Phone</label>
                    <input wire:model="phone" type="text" placeholder="+254 700 000 000"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">LinkedIn URL</label>
                    <input wire:model="linkedin_url" type="url" placeholder="https://linkedin.com/in/..."
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('linkedin_url') border-error @enderror bg-white">
                    @error('linkedin_url') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Bio</label>
                    <textarea wire:model="bio" rows="3" placeholder="Brief bio about yourself..."
                              class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white resize-none"></textarea>
                </div>
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 bg-[#7c3d1f] hover:bg-[#6b3218] text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm disabled:opacity-60">
                <span wire:loading.remove wire:target="save">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Save Changes
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>

    {{-- Password --}}
    <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">
        <h2 class="text-sm font-semibold text-on-surface mb-4">Change Password</h2>
        <form wire:submit="updatePassword" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Current Password *</label>
                    <input wire:model="current_password" type="password"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('current_password') border-error @enderror bg-white">
                    @error('current_password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">New Password *</label>
                    <input wire:model="password" type="password"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('password') border-error @enderror bg-white">
                    @error('password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Confirm *</label>
                    <input wire:model="password_confirmation" type="password"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>
            </div>
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 bg-[#1d4ed8] hover:bg-[#1e40af] text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm disabled:opacity-60">
                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                <span wire:loading wire:target="updatePassword">Updating...</span>
            </button>
        </form>
    </div>

    {{-- Danger zone --}}
    <div class="bg-white rounded-2xl border border-red-200 shadow-card p-6">
        <h2 class="text-sm font-semibold text-error mb-2">Danger Zone</h2>
        <p class="text-xs text-on-surface-variant mb-4">Once deleted, all your data is permanently removed. This cannot be undone.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
            @csrf @method('DELETE')
            <input type="password" name="password" placeholder="Enter your password to confirm"
                   class="w-full text-sm border border-red-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-red-400 mb-3 bg-white">
            @error('password', 'userDeletion') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            <button type="submit" class="bg-error hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                Delete My Account
            </button>
        </form>
    </div>

</div>
