<div class="space-y-5 max-w-2xl">


    @if(!$org)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-2xl mb-2 block"></i>
            <p class="text-amber-800 font-medium">You are not linked to any organization.</p>
            <p class="text-xs text-amber-600 mt-1">Contact the HALI Secretariat to be added.</p>
        </div>
    @else
        <form wire:submit="save" class="space-y-5">

            {{-- Logo --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">
                <h2 class="text-sm font-semibold text-on-surface mb-4">Organization Logo</h2>
                <div class="flex items-center gap-4">
                    <img src="{{ $org->logo_url }}" alt="{{ $org->name }}"
                         class="w-16 h-16 rounded-xl object-cover border-2 border-surface-container-high" id="logo-preview">
                    <div>
                        <label class="cursor-pointer text-sm bg-white border border-outline-variant text-on-surface px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors font-medium" id="logo-label">
                            Change logo
                            <input type="file" wire:model="logo" accept="image/*" class="hidden"
                                   onchange="
                                       document.getElementById('logo-preview').src = URL.createObjectURL(this.files[0]);
                                       document.getElementById('logo-label').textContent = this.files[0].name.length > 20 ? this.files[0].name.substring(0,20)+'…' : this.files[0].name;
                                       document.getElementById('logo-staged').classList.remove('hidden');
                                   ">
                        </label>
                        <p class="text-xs text-on-surface-variant mt-1">Recommended: 400×400px square</p>
                        <p id="logo-staged" class="hidden mt-1 text-xs text-teal-600 font-medium">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Logo selected — click Save to apply
                        </p>
                        @error('logo') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="logo" class="mt-1 text-xs text-primary inline-flex items-center gap-1">
                            <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Processing...
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
                <h2 class="text-sm font-semibold text-on-surface">Organization Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Organization Name *</label>
                        <input wire:model="name" type="text" required
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('name') border-error @enderror bg-white">
                        @error('name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Country</label>
                        <input wire:model="country" type="text"
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Region</label>
                        <input wire:model="region" type="text" placeholder="East Africa"
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Website</label>
                        <input wire:model="website_url" type="url"
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('website_url') border-error @enderror bg-white">
                        @error('website_url') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Founding Year</label>
                        <input wire:model="founding_year" type="number" min="1900" max="{{ date('Y') }}"
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Students Supported</label>
                        <input wire:model="students_supported" type="number" min="0"
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Description</label>
                        <textarea wire:model="description" rows="4"
                                  class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white resize-none"></textarea>
                    </div>
                </div>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 bg-[#7c3d1f] hover:bg-[#6b3218] text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Save Organization
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

        {{-- Team Members (read-only) --}}
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">
            <h2 class="text-sm font-semibold text-on-surface mb-4 flex items-center gap-2">
                <i class="fa-solid fa-users text-blue-500"></i>
                Team Members
            </h2>
            @if($teamMembers->isEmpty())
                <p class="text-sm text-on-surface-variant">No team members yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($teamMembers as $member)
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-surface-container-high">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}"
                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-on-surface">{{ $member->name }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $member->title ?? ucfirst(str_replace('_', ' ', $member->pivot->role)) }}</p>
                            </div>
                            <span class="text-xs text-outline hidden sm:block">{{ $member->email }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
