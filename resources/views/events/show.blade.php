<x-app-layout :title="$event->title">
    <div class="max-w-4xl">
        {{-- Breadcrumb --}}
        <nav class="text-xs text-hali-text-secondary mb-4 flex items-center gap-1.5">
            <a href="{{ route('events.index') }}" class="hover:text-primary">Events</a>
            <span>/</span>
            <span class="text-hali-text-primary">{{ $event->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main --}}
            <div class="lg:col-span-2 space-y-5">
                @if($event->cover_image)
                    <img src="{{ route('files.serve', ['path' => $event->cover_image]) }}" alt="{{ $event->title }}"
                         class="w-full rounded-2xl object-cover max-h-72">
                @endif

                <div class="bg-white rounded-xl border border-hali-border shadow-card p-6">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $event->type_badge_color }}">{{ ucfirst($event->type) }}</span>
                        @if($event->is_featured)
                            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">Featured</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-hali-text-primary mb-4">{{ $event->title }}</h1>
                    <div class="prose prose-sm max-w-none text-hali-text-secondary">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                    @if($event->content)
                        <div class="prose prose-sm max-w-none text-hali-text-secondary mt-4 pt-4 border-t border-hali-border">
                            {!! nl2br(e($event->content)) !!}
                        </div>
                    @endif
                </div>

                {{-- ── Event Program / Agenda ── --}}
                @if($event->programs->isNotEmpty())
                    <div class="bg-white rounded-xl border border-hali-border shadow-card p-6">
                        <h2 class="text-base font-bold text-hali-text-primary mb-5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Programme
                        </h2>
                        <ol class="space-y-4">
                            @foreach($event->programs as $item)
                                <li class="flex gap-4">
                                    {{-- time column --}}
                                    <div class="w-20 flex-shrink-0 text-right">
                                        @if($item->time_range)
                                            <span class="text-xs font-mono text-hali-text-secondary leading-5">{{ $item->time_range }}</span>
                                        @else
                                            <span class="text-xs text-hali-text-secondary leading-5">—</span>
                                        @endif
                                    </div>
                                    {{-- dot --}}
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 rounded-full bg-primary mt-1 flex-shrink-0"></div>
                                        @if(!$loop->last)
                                            <div class="w-px flex-1 bg-hali-border mt-1"></div>
                                        @endif
                                    </div>
                                    {{-- content --}}
                                    <div class="pb-4 flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-hali-text-primary leading-snug">{{ $item->title }}</p>
                                        @if($item->speaker)
                                            <p class="text-xs text-primary mt-0.5">
                                                {{ $item->speaker }}
                                                @if($item->speaker_title)
                                                    <span class="text-hali-text-secondary"> · {{ $item->speaker_title }}</span>
                                                @endif
                                            </p>
                                        @endif
                                        @if($item->description)
                                            <p class="text-xs text-hali-text-secondary mt-1 leading-relaxed">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                {{-- Event details card --}}
                <div class="bg-white rounded-xl border border-hali-border shadow-card p-5">
                    <h3 class="font-semibold text-hali-text-primary mb-4">Event Details</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="font-medium">{{ $event->start_datetime->format('l, F j, Y') }}</p>
                                <p class="text-hali-text-secondary">{{ $event->start_datetime->format('g:i A') }}
                                    @if($event->end_datetime) – {{ $event->end_datetime->format('g:i A') }} @endif
                                    {{ $event->timezone }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <p class="font-medium capitalize">{{ $event->location_type }}</p>
                                @if($event->venue_name)
                                    <p class="text-hali-text-secondary">{{ $event->venue_name }}</p>
                                    @if($event->venue_address)
                                        <p class="text-hali-text-secondary text-xs">{{ $event->venue_address }}</p>
                                    @endif
                                @endif
                                @if($userRegistration && $event->virtual_link && in_array($event->location_type, ['virtual','hybrid']))
                                    <a href="{{ $event->virtual_link }}" target="_blank" rel="noopener"
                                       class="mt-1 inline-flex items-center gap-1 text-xs text-primary hover:underline font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Join link (registered attendees only)
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Attendee count is rendered reactively by the Livewire component below --}}
                    </div>

                    {{-- Add to calendar --}}
                    <a href="{{ route('events.show', $event) }}"
                       class="mt-4 w-full flex items-center justify-center gap-2 text-sm border border-hali-border rounded-lg py-2 text-hali-text-secondary hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add to Calendar
                    </a>
                </div>

                {{-- Registration CTA — Livewire (no page reload) --}}
                <livewire:event-registration-form :event="$event" />
            </div>
        </div>
    </div>
</x-app-layout>
