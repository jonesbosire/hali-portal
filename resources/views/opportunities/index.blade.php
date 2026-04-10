<x-app-layout title="Opportunities">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Opportunities</h1>
        <a href="{{ route('opportunities.create') }}"
           class="inline-flex items-center gap-1.5 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Post Opportunity
        </a>
    </div>

    {{-- Type filter --}}
    <div class="flex flex-wrap gap-2 mb-5">
        <a href="{{ route('opportunities.index') }}"
           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ !request('type') ? 'bg-primary text-white' : 'bg-white border border-hali-border text-hali-text-secondary hover:text-primary hover:border-primary' }}">All</a>
        @foreach(['job','fellowship','scholarship','internship','volunteer'] as $t)
            <a href="{{ route('opportunities.index', ['type' => $t]) }}"
               class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors capitalize {{ request('type') === $t ? 'bg-primary text-white' : 'bg-white border border-hali-border text-hali-text-secondary hover:text-primary hover:border-primary' }}">
                {{ ucfirst($t) }}
            </a>
        @endforeach
    </div>

    @if($opportunities->isEmpty())
        <div class="bg-white rounded-xl border border-hali-border p-12 text-center shadow-card">
            <p class="text-hali-text-secondary">No opportunities found</p>
        </div>
    @else
        <div class="space-y-3 mb-6">
            @foreach($opportunities as $opp)
                <a href="{{ route('opportunities.show', $opp) }}"
                   class="bg-white rounded-xl border border-hali-border shadow-card hover:shadow-card-hover transition-shadow p-5 flex items-start gap-4 group block">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div>
                                <h3 class="font-semibold text-hali-text-primary group-hover:text-primary transition-colors">{{ $opp->title }}</h3>
                                <p class="text-sm text-hali-text-secondary">{{ $opp->organization?->name }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2 flex-shrink-0">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $opp->type_badge_color }}">{{ ucfirst($opp->type) }}</span>
                                @if($opp->is_members_only)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Members only</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3 mt-2 text-xs text-hali-text-secondary">
                            @if($opp->location)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $opp->location }}
                                </span>
                            @endif
                            @if($opp->salary_range)
                                <span>{{ $opp->salary_range }}</span>
                            @endif
                            @if($opp->deadline_at)
                                <span class="flex items-center gap-1 {{ $opp->deadline_at->diffInDays(now()) < 7 ? 'text-red-500' : '' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Deadline: {{ $opp->deadline_at->format('M j, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $opportunities->links() }}
    @endif
</x-app-layout>
