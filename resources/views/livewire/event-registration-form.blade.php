<div>
    @if($event->max_attendees)
        <div class="flex items-center gap-3 text-sm mb-3 px-1">
            <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>{{ $attendeeCount }} / {{ $event->max_attendees }} registered</span>
        </div>
    @endif

    @if($registered && $registration)
        {{-- Already registered state --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                </div>
                <div>
                    <p class="font-bold text-sm text-emerald-800">You're registered!</p>
                    <p class="text-xs text-emerald-600 capitalize">Status: {{ $registration->status }}</p>
                </div>
            </div>
            @if($registration->status !== 'canceled')
                <button wire:click="cancel"
                        wire:confirm="Cancel your registration for this event?"
                        wire:loading.attr="disabled"
                        class="text-xs text-red-600 hover:text-red-700 font-medium hover:underline transition-colors">
                    <span wire:loading.remove wire:target="cancel">Cancel registration</span>
                    <span wire:loading wire:target="cancel">Canceling...</span>
                </button>
            @endif
        </div>

    @elseif($event->isRegistrationOpen() && !$event->isFull())
        {{-- Registration form --}}
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-5">
            <h3 class="font-semibold text-sm text-on-surface mb-4 flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus text-violet-500"></i>
                Register for this Event
            </h3>
            <form wire:submit="register" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Dietary requirements</label>
                    <input wire:model="dietary_requirements" type="text"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-violet-500/20 bg-white"
                           placeholder="Optional">
                    @error('dietary_requirements') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Notes</label>
                    <textarea wire:model="registration_notes" rows="2"
                              class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-violet-500/20 bg-white resize-none"
                              placeholder="Optional"></textarea>
                    @error('registration_notes') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition-colors text-sm disabled:opacity-60">
                    <span wire:loading.remove wire:target="register">
                        <i class="fa-solid fa-arrow-right mr-1"></i> Register Now
                    </span>
                    <span wire:loading wire:target="register" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Registering...
                    </span>
                </button>
            </form>
        </div>

    @elseif($event->isFull())
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center">
            <i class="fa-solid fa-users-slash text-amber-500 text-2xl mb-2 block"></i>
            <p class="text-sm font-semibold text-amber-800">This event is full</p>
            <p class="text-xs text-amber-600 mt-1">Waitlist registrations may be accepted</p>
        </div>

    @else
        <div class="bg-surface-container rounded-2xl p-5 text-center">
            <i class="fa-solid fa-clock text-outline text-2xl mb-2 block"></i>
            <p class="text-sm text-on-surface-variant">Registration is not currently open</p>
        </div>
    @endif
</div>
