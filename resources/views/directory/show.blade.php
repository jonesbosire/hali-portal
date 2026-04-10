<x-app-layout :title="$organization->name">
    <div class="max-w-4xl">
        <nav class="text-xs text-hali-text-secondary mb-4 flex items-center gap-1.5">
            <a href="{{ route('directory.index') }}" class="hover:text-primary">Directory</a>
            <span>/</span>
            <span class="text-hali-text-primary">{{ $organization->name }}</span>
        </nav>

        {{-- Header card --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 mb-6">
            <div class="flex items-start gap-5">
                <img src="{{ $organization->logo_url }}" alt="{{ $organization->name }}"
                     class="w-20 h-20 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-2xl font-bold text-hali-text-primary">{{ $organization->name }}</h1>
                        <span class="text-sm px-2.5 py-0.5 rounded-full {{ $organization->type === 'member' ? 'bg-primary-50 text-primary' : 'bg-orange-50 text-orange-700' }}">
                            {{ ucfirst($organization->type) }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-hali-text-secondary">
                        @if($organization->country)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $organization->country }}{{ $organization->region ? ', '.$organization->region : '' }}
                            </span>
                        @endif
                        @if($organization->founding_year)
                            <span>Est. {{ $organization->founding_year }}</span>
                        @endif
                        @if($organization->students_supported)
                            <span class="text-primary font-medium">{{ number_format($organization->students_supported) }} students supported</span>
                        @endif
                    </div>
                    @if($organization->website_url)
                        <a href="{{ $organization->website_url }}" target="_blank" rel="noopener"
                           class="mt-2 inline-flex items-center gap-1 text-sm text-primary hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            {{ parse_url($organization->website_url, PHP_URL_HOST) }}
                        </a>
                    @endif
                </div>
            </div>

            @if($organization->description)
                <div class="mt-5 pt-5 border-t border-hali-border">
                    <p class="text-sm text-hali-text-secondary leading-relaxed">{{ $organization->description }}</p>
                </div>
            @endif

            @php $listing = $organization->directoryListing; @endphp
            @if($listing && ($listing->specializations || $listing->countries_served || $listing->languages))
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    @if($listing->specializations)
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Specializations</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($listing->specializations as $spec)
                                    <span class="bg-gray-100 text-hali-text-secondary text-xs px-2 py-0.5 rounded-full">{{ $spec }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($listing->countries_served)
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Countries Served</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($listing->countries_served as $c)
                                    <span class="bg-primary-50 text-primary text-xs px-2 py-0.5 rounded-full">{{ $c }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 space-y-5">
                {{-- Team --}}
                @if($teamMembers->isNotEmpty())
                    <div class="bg-white rounded-xl border border-hali-border shadow-card p-5">
                        <h2 class="text-sm font-semibold text-hali-text-primary mb-4">Team Members</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($teamMembers as $member)
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-hali-border">
                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}"
                                         class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-hali-text-primary truncate">{{ $member->name }}</p>
                                        <p class="text-xs text-hali-text-secondary">{{ $member->title ?? ucfirst(str_replace('_', ' ', $member->pivot->role)) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Active Opportunities --}}
                @if($organization->opportunities->isNotEmpty())
                    <div class="bg-white rounded-xl border border-hali-border shadow-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-semibold text-hali-text-primary">Current Opportunities</h2>
                            <a href="{{ route('opportunities.index') }}" class="text-xs text-primary hover:underline">View all</a>
                        </div>
                        <div class="space-y-3">
                            @foreach($organization->opportunities as $opp)
                                <a href="{{ route('opportunities.show', $opp) }}"
                                   class="flex items-center gap-3 p-3 rounded-lg border border-hali-border hover:bg-gray-50 transition-colors">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $opp->type_badge_color }}">{{ ucfirst($opp->type) }}</span>
                                    <span class="text-sm font-medium text-hali-text-primary flex-1 truncate">{{ $opp->title }}</span>
                                    @if($opp->deadline_at)
                                        <span class="text-xs text-gray-400 flex-shrink-0">Due {{ $opp->deadline_at->format('M j') }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Contact sidebar --}}
            @if($listing && ($listing->linkedin_url || $listing->twitter_url))
                <div class="bg-white rounded-xl border border-hali-border shadow-card p-5 h-fit">
                    <h2 class="text-sm font-semibold text-hali-text-primary mb-4">Connect</h2>
                    @if($listing->linkedin_url)
                        <a href="{{ $listing->linkedin_url }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 text-sm text-hali-text-secondary hover:text-primary py-2 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                    @endif
                    @if($listing->twitter_url)
                        <a href="{{ $listing->twitter_url }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 text-sm text-hali-text-secondary hover:text-primary py-2 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.259 5.629L18.244 2.25z"/></svg>
                            X / Twitter
                        </a>
                    @endif

                    @if(auth()->user()->primaryOrganization()?->id === $organization->id)
                        <div class="mt-4 pt-4 border-t border-hali-border">
                            <a href="{{ route('organization.edit') }}"
                               class="block text-center text-xs bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                                Update My Listing
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
