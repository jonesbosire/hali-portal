<x-app-layout :title="$opportunity->title">
    <div class="max-w-3xl">
        <nav class="text-xs text-hali-text-secondary mb-4 flex items-center gap-1.5">
            <a href="{{ route('opportunities.index') }}" class="hover:text-primary">Opportunities</a>
            <span>/</span>
            <span class="text-hali-text-primary truncate">{{ $opportunity->title }}</span>
        </nav>

        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 mb-5">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="text-sm px-3 py-1 rounded-full {{ $opportunity->type_badge_color }} font-medium">{{ ucfirst($opportunity->type) }}</span>
                        @if($opportunity->is_members_only)
                            <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-500">Members only</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-hali-text-primary">{{ $opportunity->title }}</h1>
                    <p class="text-hali-text-secondary mt-1">{{ $opportunity->organization?->name }}</p>
                </div>
                @if($opportunity->application_url)
                    <a href="{{ $opportunity->application_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white font-semibold px-5 py-2.5 rounded-xl transition-colors flex-shrink-0">
                        Apply Now →
                    </a>
                @endif
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-hali-text-secondary pb-4 border-b border-hali-border mb-4">
                @if($opportunity->location)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $opportunity->location }}
                    </span>
                @endif
                @if($opportunity->salary_range)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $opportunity->salary_range }}
                    </span>
                @endif
                @if($opportunity->deadline_at)
                    <span class="flex items-center gap-1.5 {{ $opportunity->deadline_at->diffInDays(now()) < 7 ? 'text-red-500' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deadline: {{ $opportunity->deadline_at->format('F j, Y') }}
                        @if($opportunity->deadline_at->diffInDays(now()) < 7)
                            ({{ $opportunity->deadline_at->diffForHumans() }})
                        @endif
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Posted {{ $opportunity->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="prose prose-sm max-w-none text-hali-text-secondary">
                {!! nl2br(e($opportunity->description)) !!}
            </div>

            @if($opportunity->requirements)
                <div class="mt-5 pt-5 border-t border-hali-border">
                    <h3 class="font-semibold text-hali-text-primary mb-2">Requirements</h3>
                    <div class="prose prose-sm max-w-none text-hali-text-secondary">
                        {!! nl2br(e($opportunity->requirements)) !!}
                    </div>
                </div>
            @endif

            @if($opportunity->application_url)
                <div class="mt-6 pt-5 border-t border-hali-border flex justify-center">
                    <a href="{{ $opportunity->application_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white font-semibold px-8 py-3 rounded-xl transition-colors">
                        Apply for This Position →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
