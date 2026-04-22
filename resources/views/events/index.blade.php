<x-app-layout title="Events">

    {{-- ── Featured event hero banner ── --}}
    @if($featuredEvent)
        <section class="relative rounded-3xl overflow-hidden min-h-[360px] flex items-end group shadow-2xl shadow-primary/10 mb-8">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
            @if($featuredEvent->cover_image)
                <img src="{{ route('files.serve', ['path' => $featuredEvent->cover_image]) }}"
                     alt="{{ $featuredEvent->title }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-container"></div>
            @endif
            <div class="relative z-20 p-8 md:p-12 w-full space-y-4">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-container text-on-secondary-container font-headline font-bold text-xs uppercase tracking-widest shadow-lg">
                    <i class="fa-solid fa-star text-xs"></i>
                    Featured {{ ucfirst($featuredEvent->type) }}
                </span>
                <h2 class="text-4xl md:text-5xl font-headline font-bold text-white leading-tight max-w-3xl">
                    {{ $featuredEvent->title }}
                </h2>
                @if($featuredEvent->excerpt)
                    <p class="text-lg text-white/80 max-w-2xl leading-relaxed">{{ $featuredEvent->excerpt }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    @if($featuredEvent->isRegistrationOpen())
                        <a href="{{ route('events.show', $featuredEvent) }}"
                           class="px-8 py-3 bg-gradient-to-r from-primary to-primary-container text-white rounded-xl font-bold text-base shadow-xl hover:shadow-primary/40 transition-all flex items-center gap-2">
                            Register Now
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('events.show', $featuredEvent) }}"
                           class="px-8 py-3 bg-white/20 backdrop-blur text-white rounded-xl font-bold text-base hover:bg-white/30 transition-all">
                            Learn More →
                        </a>
                    @endif
                    <div class="flex items-center gap-3 text-white">
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl">
                            <i class="fa-solid fa-calendar text-secondary-container text-[15px]"></i>
                            <span class="text-sm font-semibold">{{ $featuredEvent->start_datetime->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl">
                            <i class="fa-solid fa-location-dot text-secondary-container text-[15px]"></i>
                            <span class="text-sm font-semibold">
                                {{ $featuredEvent->location_type === 'online' ? 'Online' : ($featuredEvent->location ?? 'In-Person') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── Left filter sidebar ── --}}
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-4">
            <form method="GET" id="filter-form">
                <div class="bg-surface-container-lowest p-6 rounded-2xl space-y-8">

                    <div>
                        <h3 class="font-headline font-bold text-on-surface mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-primary"></i>
                            Event Type
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="type" value=""
                                       {{ !request('type') ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()"
                                       class="text-primary focus:ring-primary/20 w-5 h-5">
                                <span class="text-sm font-medium text-on-surface-variant group-hover:text-primary transition-colors">All Events</span>
                            </label>
                            @foreach(['webinar' => 'Webinars', 'conference' => 'Conferences', 'workshop' => 'Workshops', 'indaba' => 'Indabas', 'other' => 'Other'] as $val => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="type" value="{{ $val }}"
                                           {{ request('type') == $val ? 'checked' : '' }}
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="text-primary focus:ring-primary/20 w-5 h-5">
                                    <span class="text-sm font-medium text-on-surface-variant group-hover:text-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] uppercase tracking-widest text-outline font-bold mb-4">Timeframe</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="upcoming" value="1"
                                       {{ request('upcoming') ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()"
                                       class="rounded border-outline-variant text-primary focus:ring-primary/20 w-5 h-5">
                                <span class="text-sm font-medium text-on-surface-variant">Upcoming Only</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="past" value="1"
                                       {{ request('past') ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()"
                                       class="rounded border-outline-variant text-primary focus:ring-primary/20 w-5 h-5">
                                <span class="text-sm font-medium text-on-surface-variant">Past Events</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="open" value="1"
                                       {{ request('open') ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()"
                                       class="rounded border-outline-variant text-primary focus:ring-primary/20 w-5 h-5">
                                <span class="text-sm font-medium text-on-surface-variant">Open Registration</span>
                            </label>
                        </div>
                    </div>

                    @if(request()->hasAny(['type', 'upcoming', 'past', 'open']))
                        <a href="{{ route('events.index') }}"
                           class="block w-full py-3 bg-surface-container-high text-primary font-bold rounded-xl text-sm text-center hover:bg-primary hover:text-white transition-all">
                            Reset Filters
                        </a>
                    @endif
                </div>

                {{-- Alert promo --}}
                <div class="mt-4 bg-primary p-5 rounded-2xl text-white space-y-2">
                    <p class="font-headline font-bold text-base leading-tight">New events are added regularly</p>
                    <p class="text-xs text-white/70 leading-relaxed">Check back often to see what is coming up.</p>
                </div>
            </form>
        </aside>

        {{-- ── Event grid ── --}}
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-headline font-bold text-on-surface">Events</h3>
                    <span class="px-2 py-0.5 rounded-lg bg-primary/10 text-primary text-[10px] font-bold uppercase">
                        {{ $events->total() }} EVENTS
                    </span>
                </div>
                {{-- View toggle --}}
                <div class="flex items-center gap-1">
                    <span class="px-3 py-2 rounded-xl text-sm font-semibold bg-primary text-white flex items-center gap-1.5">
                        <i class="fa-solid fa-grip text-[13px]"></i>
                        <span class="hidden sm:inline">List</span>
                    </span>
                    <a href="{{ route('events.index', ['view' => 'calendar']) }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low border border-outline-variant/30 transition-colors">
                        <i class="fa-solid fa-calendar-days text-[13px]"></i>
                        <span class="hidden sm:inline">Calendar</span>
                    </a>
                </div>
            </div>

            @if($events->isEmpty())
                <div class="bg-surface-container-lowest rounded-2xl p-16 text-center">
                    <i class="fa-solid fa-calendar-xmark text-outline text-5xl block mb-3"></i>
                    <p class="text-on-surface-variant font-medium">No events found matching your filters</p>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block text-primary text-sm font-bold hover:underline">Clear filters</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($events as $event)
                        <div class="group bg-surface-container-lowest rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-primary/5 flex flex-col">
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-primary to-primary-container">
                                @if($event->cover_image)
                                    <img src="{{ route('files.serve', ['path' => $event->cover_image]) }}"
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-calendar-day text-white/30 text-6xl"></i>
                                    </div>
                                @endif
                                {{-- Type badge --}}
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-primary-container text-on-primary-container text-[10px] font-bold uppercase rounded-full tracking-wider">
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </div>
                                {{-- Date badge --}}
                                <div class="absolute bottom-4 right-4 px-3 py-2 bg-white/90 backdrop-blur rounded-xl text-center shadow-lg">
                                    <p class="text-[10px] font-bold text-primary uppercase leading-none">{{ $event->start_datetime->format('M') }}</p>
                                    <p class="text-lg font-headline font-extrabold text-on-surface leading-tight">{{ $event->start_datetime->format('d') }}</p>
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-outline mb-3">
                                    <i class="fa-solid {{ $event->location_type === 'online' ? 'fa-laptop' : 'fa-location-dot' }} text-[14px]"></i>
                                    <span class="text-xs font-semibold">
                                        {{ $event->location_type === 'online' ? 'Virtual' : ($event->location ?? 'In-Person') }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-headline font-bold text-on-surface mb-2 group-hover:text-primary transition-colors leading-snug line-clamp-2">
                                    {{ $event->title }}
                                </h4>
                                @if($event->excerpt)
                                    <p class="text-sm text-on-surface-variant line-clamp-2 mb-4 leading-relaxed">{{ $event->excerpt }}</p>
                                @endif
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-outline-variant/10">
                                    @if($event->price_cents > 0)
                                        <span class="text-xs font-bold text-on-surface">
                                            ${{ number_format($event->price_cents / 100, 2) }}
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-on-surface">FREE</span>
                                    @endif
                                    @if($event->isRegistrationOpen())
                                        <a href="{{ route('events.show', $event) }}"
                                           class="px-6 py-2.5 bg-secondary-container hover:bg-[#E09412] text-on-secondary-container font-bold text-sm rounded-xl transition-all shadow-md active:scale-95">
                                            Register
                                        </a>
                                    @else
                                        <a href="{{ route('events.show', $event) }}"
                                           class="px-6 py-2.5 bg-surface-container-high text-on-surface font-bold text-sm rounded-xl transition-all hover:bg-surface-container-highest">
                                            Details
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $events->links() }}
            @endif
        </div>
    </div>

</x-app-layout>
