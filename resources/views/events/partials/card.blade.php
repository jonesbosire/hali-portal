<div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden hover:shadow-card-hover transition-shadow group">
    @if($event->cover_image)
        <div class="h-36 bg-gray-100 overflow-hidden">
            <img src="{{ asset('storage/'.$event->cover_image) }}" alt="{{ $event->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
    @else
        <div class="h-36 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-primary opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
    @endif

    <div class="p-4">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $event->type_badge_color }}">
                {{ ucfirst($event->type) }}
            </span>
            <span class="text-xs text-hali-text-secondary flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $event->location_type === 'virtual' ? 'M15 10l4.553-2.069A1 1 0 0121 8.847v6.306a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z' : 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z' }}"/></svg>
                {{ ucfirst($event->location_type) }}
            </span>
            @if($event->is_members_only)
                <span class="ml-auto text-xs text-gray-400 flex items-center gap-0.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    Members
                </span>
            @endif
        </div>

        <a href="{{ route('events.show', $event) }}" class="block">
            <h3 class="font-semibold text-hali-text-primary group-hover:text-primary transition-colors leading-snug mb-1">
                {{ $event->title }}
            </h3>
        </a>

        <p class="text-xs text-hali-text-secondary flex items-center gap-1 mt-1.5">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ $event->start_datetime->format('M j, Y · g:i A') }}
        </p>

        @if($event->max_attendees)
            <div class="mt-2.5">
                @php $pct = min(100, ($event->attendees()->count() / $event->max_attendees) * 100) @endphp
                <div class="flex justify-between text-xs text-hali-text-secondary mb-1">
                    <span>{{ $event->attendees()->count() }} registered</span>
                    <span>{{ $event->max_attendees }} max</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-primary h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endif

        <div class="mt-3 pt-3 border-t border-hali-border flex items-center justify-between">
            @if($event->isRegistrationOpen() && !$event->isFull())
                <a href="{{ route('events.show', $event) }}"
                   class="text-xs bg-accent text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-accent-dark transition-colors">
                    Register
                </a>
            @elseif($event->isFull())
                <span class="text-xs text-gray-400 font-medium">Full — Waitlist</span>
            @elseif($event->start_datetime->isPast())
                <span class="text-xs text-gray-400 font-medium">Past event</span>
            @else
                <a href="{{ route('events.show', $event) }}" class="text-xs text-primary hover:underline font-medium">Learn more</a>
            @endif
            <a href="{{ route('events.show', $event) }}" class="text-xs text-hali-text-secondary hover:text-primary">Details →</a>
        </div>
    </div>
</div>
