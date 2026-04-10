<x-app-layout title="Member Directory">

    {{-- ── Page header ── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="font-headline text-3xl font-bold text-on-surface tracking-tight">Member Directory</h1>
            <p class="text-on-surface-variant mt-1">{{ $organizations->total() }} member organizations across the HALI network</p>
        </div>
    </div>

    {{-- ── Search & filter bar ── --}}
    <form method="GET" class="bg-surface-container-lowest rounded-2xl p-5 mb-8 flex flex-wrap gap-3 items-center shadow-card">
        {{-- Search input --}}
        <div class="relative flex-1 min-w-64">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[16px]"></i>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Search by name, country or mission..."
                   class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-none rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/20 placeholder:text-outline transition-all">
        </div>

        {{-- Country filter --}}
        <select name="country"
                class="bg-surface-container-low border-none rounded-xl text-sm font-medium px-4 py-3 focus:ring-2 focus:ring-primary/20 text-on-surface">
            <option value="">All countries</option>
            @foreach($countries as $country)
                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
            @endforeach
        </select>

        {{-- Type filter --}}
        <select name="type"
                class="bg-surface-container-low border-none rounded-xl text-sm font-medium px-4 py-3 focus:ring-2 focus:ring-primary/20 text-on-surface">
            <option value="">All types</option>
            <option value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Member Organizations</option>
            <option value="friend" {{ request('type') == 'friend' ? 'selected' : '' }}>Friends of HALI</option>
        </select>

        <button type="submit"
                class="bg-primary text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass text-[15px]"></i>
            Search
        </button>

        @if(request()->hasAny(['q', 'country', 'type']))
            <a href="{{ route('directory.index') }}"
               class="px-4 py-3 rounded-xl text-sm font-bold text-on-surface-variant border border-outline-variant/30 hover:border-primary hover:text-primary transition-all">
                Clear
            </a>
        @endif
    </form>

    {{-- ── Directory grid ── --}}
    @if($organizations->isEmpty())
        <div class="bg-surface-container-lowest rounded-2xl p-16 text-center">
            <i class="fa-solid fa-user-slash text-outline text-5xl block mb-3"></i>
            <p class="text-on-surface-variant font-medium">No organizations found</p>
            @if(request()->hasAny(['q', 'country', 'type']))
                <a href="{{ route('directory.index') }}" class="mt-3 inline-block text-primary text-sm font-bold hover:underline">Clear search</a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @foreach($organizations as $org)
                <a href="{{ route('directory.show', $org->slug) }}"
                   class="group bg-surface-container-lowest rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-primary/5 transition-all duration-300 flex flex-col">

                    {{-- Organization header stripe --}}
                    <div class="h-2 w-full {{ $org->type === 'member' ? 'bg-gradient-to-r from-primary to-primary-container' : 'bg-gradient-to-r from-secondary to-secondary-container' }}"></div>

                    <div class="p-6 flex-1 flex flex-col">
                        {{-- Logo + name --}}
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-surface-container bg-surface-container flex-shrink-0">
                                <img src="{{ $org->logo_url }}" alt="{{ $org->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-headline font-bold text-on-surface group-hover:text-primary transition-colors leading-tight line-clamp-2">
                                    {{ $org->name }}
                                </h3>
                                <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-location-dot text-[12px]"></i>
                                    {{ $org->country ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Membership badge --}}
                        <div class="mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                {{ $org->type === 'member'
                                    ? 'bg-primary/10 text-primary'
                                    : 'bg-secondary-container/30 text-secondary' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $org->type === 'member' ? 'bg-primary' : 'bg-secondary' }}"></span>
                                {{ ucfirst($org->type) }} Organization
                            </span>
                        </div>

                        {{-- Description --}}
                        @if($org->description)
                            <p class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed flex-1">{{ $org->description }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        {{-- Primary contact footer --}}
                        @if($primaryContact = $org->primaryContact())
                            <div class="mt-4 pt-4 border-t border-outline-variant/10 flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full overflow-hidden ring-2 ring-outline-variant/20 flex-shrink-0">
                                    <img src="{{ $primaryContact->avatar_url }}" alt="{{ $primaryContact->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-on-surface truncate">{{ $primaryContact->name }}</p>
                                    @if($primaryContact->title)
                                        <p class="text-[10px] text-on-surface-variant truncate">{{ $primaryContact->title }}</p>
                                    @endif
                                </div>
                                <i class="fa-solid fa-arrow-right text-outline text-[15px] group-hover:text-primary transition-colors"></i>
                            </div>
                        @else
                            <div class="mt-4 pt-4 border-t border-outline-variant/10 flex items-center justify-end">
                                <span class="text-xs font-bold text-primary flex items-center gap-1 group-hover:gap-2 transition-all">
                                    View Profile
                                    <i class="fa-solid fa-arrow-right text-[13px]"></i>
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        {{ $organizations->links() }}
    @endif

</x-app-layout>
