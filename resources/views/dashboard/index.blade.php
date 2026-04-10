<x-app-layout title="Dashboard">

    {{-- ── Greeting bar ── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-on-surface tracking-tight">
                Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-on-surface-variant mt-1 text-sm">
                {{ now()->format('l, F j, Y') }}
                @if(auth()->user()->primaryOrganization())
                    · <span class="font-medium text-primary">{{ auth()->user()->primaryOrganization()->name }}</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Active Member
            </span>
            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-[#6b3218] transition-colors">
                <i class="fa-solid fa-bullhorn text-[13px]"></i>
                <span class="hidden sm:inline">Stories & Updates</span>
                <span class="sm:hidden">Updates</span>
            </a>
        </div>
    </div>

    {{-- ── Admin stats bar ── --}}
    @if($adminStats)
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4 mb-8">
            @php $adminWidgets = [
                ['label' => 'Total Members',        'value' => $adminStats['total_members'],       'icon' => 'fa-users',          'bg' => 'bg-blue-50',    'icon_color' => 'text-blue-600',   'val_color' => 'text-blue-700'],
                ['label' => 'Pending Approval',     'value' => $adminStats['pending_members'],      'icon' => 'fa-hourglass-half', 'bg' => 'bg-amber-50',   'icon_color' => 'text-amber-600',  'val_color' => 'text-amber-700'],
                ['label' => 'Upcoming Events',      'value' => $adminStats['upcoming_events'],      'icon' => 'fa-calendar-day',   'bg' => 'bg-violet-50',  'icon_color' => 'text-violet-600', 'val_color' => 'text-violet-700'],
                ['label' => 'Active Opportunities', 'value' => $adminStats['active_opportunities'], 'icon' => 'fa-lightbulb',      'bg' => 'bg-teal-50',    'icon_color' => 'text-teal-600',   'val_color' => 'text-teal-700'],
            ]; @endphp
            @foreach($adminWidgets as $w)
                <div class="bg-white rounded-2xl p-4 md:p-5 shadow-card border border-surface-container-high flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-xl {{ $w['bg'] }} flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $w['icon'] }} {{ $w['icon_color'] }} text-base md:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-headline text-xl md:text-2xl font-bold {{ $w['val_color'] }}">{{ $w['value'] }}</p>
                        <p class="text-[11px] md:text-xs text-on-surface-variant leading-tight">{{ $w['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-6 xl:gap-8">

        {{-- ── Main area ── --}}
        <div class="w-full xl:w-[68%] space-y-8">

            {{-- Quick Access Grid --}}
            <div>
                <h3 class="font-headline text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-table-cells-large text-[#7c3d1f]"></i>
                    Quick Access
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php $quickLinks = [
                        ['route' => 'directory.index',    'label' => 'Directory',       'sub' => 'Connect with partners', 'icon' => 'fa-users',          'bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'text' => 'text-blue-600'],
                        ['route' => 'events.index',       'label' => 'Events',          'sub' => 'Workshops & Summits',   'icon' => 'fa-calendar-day',   'bg' => 'bg-violet-500', 'light' => 'bg-violet-50', 'text' => 'text-violet-600'],
                        ['route' => 'opportunities.index','label' => 'Opportunities',   'sub' => 'Grants & Fellowships',  'icon' => 'fa-lightbulb',      'bg' => 'bg-amber-500',  'light' => 'bg-amber-50',  'text' => 'text-amber-600'],
                        ['route' => 'resources.index',    'label' => 'Resources',       'sub' => 'Guides & Templates',    'icon' => 'fa-folder-open',    'bg' => 'bg-teal-500',   'light' => 'bg-teal-50',   'text' => 'text-teal-600'],
                        ['route' => 'billing.index',      'label' => 'Billing',         'sub' => 'Dues & Invoices',       'icon' => 'fa-credit-card',    'bg' => 'bg-emerald-500','light' => 'bg-emerald-50','text' => 'text-emerald-600'],
                        ['route' => 'organization.edit',  'label' => 'My Org',          'sub' => 'Profile & Team',        'icon' => 'fa-building-columns','bg' => 'bg-[#7c3d1f]',  'light' => 'bg-orange-50', 'text' => 'text-orange-700'],
                    ]; @endphp
                    @foreach($quickLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="bg-white border border-surface-container-high p-4 md:p-5 rounded-2xl group hover:shadow-card-hover hover:-translate-y-0.5 transition-all duration-200 flex flex-col items-start gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $link['light'] }} flex items-center justify-center group-hover:{{ $link['bg'] }} group-hover:text-white transition-all duration-200">
                                <i class="fa-solid {{ $link['icon'] }} {{ $link['text'] }} group-hover:text-white text-base transition-colors"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-sm text-on-surface">{{ $link['label'] }}</span>
                                <span class="text-[11px] text-on-surface-variant leading-tight">{{ $link['sub'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Upcoming Events --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-headline text-base font-bold text-on-surface flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-violet-500"></i>
                        Upcoming Events
                    </h3>
                    <a href="{{ route('events.index') }}" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="bg-white border border-surface-container-high rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:shadow-card transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-violet-50 flex flex-col items-center justify-center text-violet-600 flex-shrink-0">
                                    <span class="text-[10px] font-bold uppercase">{{ $event->start_datetime->format('M') }}</span>
                                    <span class="text-xl font-bold font-headline leading-tight">{{ $event->start_datetime->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface">{{ $event->title }}</h4>
                                    <p class="text-xs text-on-surface-variant flex items-center gap-1.5 mt-0.5">
                                        <i class="fa-solid {{ $event->location_type === 'online' ? 'fa-video' : 'fa-location-dot' }} text-[11px]"></i>
                                        {{ $event->start_datetime->format('g:i A') }} ·
                                        {{ $event->location_type === 'online' ? 'Online' : ($event->location ?? ucfirst($event->location_type)) }}
                                    </p>
                                </div>
                            </div>
                            @if($event->isRegistrationOpen())
                                <a href="{{ route('events.show', $event) }}"
                                   class="flex-shrink-0 bg-violet-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-violet-700 transition-colors text-center">
                                    Register
                                </a>
                            @else
                                <a href="{{ route('events.show', $event) }}"
                                   class="flex-shrink-0 border border-outline-variant px-4 py-2 rounded-xl text-xs font-bold text-on-surface-variant hover:border-primary hover:text-primary transition-colors text-center">
                                    View
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white border border-surface-container-high rounded-xl p-10 text-center">
                            <i class="fa-solid fa-calendar-xmark text-outline text-4xl mb-3 block"></i>
                            <p class="text-on-surface-variant text-sm">No upcoming events. Check back soon!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Secretariat Updates --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-headline text-base font-bold text-on-surface flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-teal-500"></i>
                        Secretariat Updates
                    </h3>
                    <a href="{{ route('posts.index') }}" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="bg-white border border-surface-container-high rounded-2xl overflow-hidden">
                    @forelse($latestPosts as $i => $post)
                        <a href="{{ route('posts.show', $post) }}"
                           class="block {{ $i > 0 ? 'border-t border-surface-container-low' : '' }} p-5 hover:bg-surface-container-lowest transition-colors group">
                            @if($i === 0)
                                <span class="text-[10px] font-bold uppercase tracking-widest text-teal-600 mb-2 block">
                                    {{ ucfirst($post->type) }}
                                </span>
                                <h4 class="font-bold text-base text-on-surface mb-1.5 group-hover:text-primary transition-colors line-clamp-2">{{ $post->title }}</h4>
                                @if($post->excerpt)
                                    <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2 mb-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-primary/10 overflow-hidden">
                                            <img src="{{ $post->author?->avatar_url ?? '' }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-xs font-semibold text-on-surface-variant">{{ $post->author?->name ?? 'HALI Secretariat' }}</span>
                                        <span class="text-xs text-outline">· {{ $post->published_at?->diffForHumans() }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-primary flex items-center gap-1 group-hover:gap-2 transition-all">
                                        Read more <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-3">
                                    @if($post->cover_image)
                                        <img src="{{ route('files.serve', ['path' => $post->cover_image]) }}" alt=""
                                             class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-newspaper text-teal-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-outline">{{ ucfirst($post->type) }}</span>
                                        <p class="text-sm font-semibold text-on-surface group-hover:text-primary transition-colors truncate">{{ $post->title }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $post->published_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endif
                        </a>
                    @empty
                        <div class="p-10 text-center">
                            <i class="fa-solid fa-bullhorn text-outline text-4xl block mb-2"></i>
                            <p class="text-on-surface-variant text-sm">No updates yet</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>

        {{-- ── Right sidebar ── --}}
        <aside class="w-full xl:w-[32%] space-y-4">

            {{-- Profile Strength --}}
            <div class="bg-white border border-surface-container-high p-5 rounded-2xl">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-sm text-on-surface">Profile Strength</h4>
                    <span class="text-primary font-bold text-sm">{{ $completeness['percent'] }}%</span>
                </div>
                <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden mb-3">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width: {{ $completeness['percent'] }}%; background: {{ $completeness['percent'] < 50 ? '#f59e0b' : ($completeness['percent'] < 80 ? '#0d6b62' : '#16a34a') }}"></div>
                </div>
                @if(count($completeness['missing']) > 0)
                    <p class="text-xs text-on-surface-variant mb-3 italic">Add {{ $completeness['missing'][0] }} to strengthen your profile</p>
                @else
                    <p class="text-xs text-emerald-600 font-semibold mb-3">✓ Profile complete!</p>
                @endif
                <a href="{{ route('profile.edit') }}"
                   class="block w-full py-2 rounded-xl border border-outline-variant text-xs font-bold text-center hover:bg-surface-container-low transition-colors text-on-surface">
                    {{ $completeness['percent'] < 100 ? 'Complete Profile' : 'Edit Profile' }}
                </a>
            </div>

            {{-- Org Card --}}
            @php $org = auth()->user()->primaryOrganization(); @endphp
            @if($org)
                <div class="bg-white border border-surface-container-high p-5 rounded-2xl text-center">
                    <div class="relative inline-block mb-3">
                        <img src="{{ $org->logo_url }}" alt="{{ $org->name }}"
                             class="w-16 h-16 rounded-xl object-cover border-2 border-surface-container-high mx-auto">
                        <a href="{{ route('organization.edit') }}"
                           class="absolute -bottom-1 -right-1 w-6 h-6 bg-[#7c3d1f] rounded-full border-2 border-white flex items-center justify-center text-white hover:bg-[#6b3218] transition-colors">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </a>
                    </div>
                    <h3 class="font-bold text-sm text-on-surface">{{ $org->name }}</h3>
                    <p class="text-xs text-on-surface-variant">{{ $org->country ?? 'Global' }}</p>
                    @php $memberCount = $org->members()->count(); @endphp
                    <div class="mt-4 pt-4 border-t border-surface-container grid grid-cols-2 gap-3">
                        <div>
                            <span class="block font-headline text-lg font-bold text-primary">{{ $memberCount }}</span>
                            <span class="text-[10px] uppercase font-bold text-on-surface-variant">Team</span>
                        </div>
                        <div>
                            <span class="block font-headline text-sm font-bold text-teal-600 capitalize">{{ $org->type }}</span>
                            <span class="text-[10px] uppercase font-bold text-on-surface-variant">Status</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Latest Opportunities --}}
            <div class="bg-white border border-surface-container-high p-5 rounded-2xl">
                <h4 class="font-bold text-sm text-on-surface mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-lightbulb text-amber-500"></i>
                    Opportunities
                </h4>
                <div class="space-y-2">
                    @forelse($latestOpportunities as $opp)
                        <a href="{{ route('opportunities.show', $opp) }}"
                           class="flex items-start gap-3 p-3 rounded-xl hover:bg-surface-container-low transition-colors group">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-lightbulb text-amber-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $opp->title }}</p>
                                @if($opp->deadline_at)
                                    <p class="text-[10px] text-amber-600 font-medium mt-0.5">Due {{ $opp->deadline_at->format('M j') }}</p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-on-surface-variant text-center py-4">No active opportunities</p>
                    @endforelse
                </div>
                <a href="{{ route('opportunities.index') }}"
                   class="block mt-3 pt-3 border-t border-surface-container text-center text-xs font-bold text-primary hover:text-[#6b3218] transition-colors">
                    Browse All →
                </a>
            </div>

            {{-- CTA --}}
            <div class="bg-gradient-to-br from-[#7c3d1f] via-[#9b4e28] to-[#0d6b62] p-5 rounded-2xl text-white">
                <h4 class="font-headline font-bold text-base mb-1">Network Highlight</h4>
                <p class="text-xs text-white/80 leading-relaxed mb-4">
                    Explore stories and impact created across the HALI Access partner network.
                </p>
                <a href="{{ route('posts.index', ['type' => 'story']) }}"
                   class="block w-full py-2 bg-white/20 hover:bg-white/30 border border-white/30 text-white text-xs font-bold rounded-xl transition-colors text-center">
                    Read Success Stories
                </a>
            </div>

        </aside>
    </div>

</x-app-layout>
