<x-app-layout title="{{ $user->name }} — Admin">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Members</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary truncate">{{ $user->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Left: Profile card ── --}}
        <div class="space-y-5">

            {{-- Identity --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover mx-auto mb-3">
                <h2 class="font-bold text-hali-text-primary text-lg leading-tight">{{ $user->name }}</h2>
                @if($user->title)
                    <p class="text-sm text-hali-text-secondary mt-0.5">{{ $user->title }}</p>
                @endif
                <p class="text-xs text-hali-text-secondary mt-1">{{ $user->email }}</p>

                <div class="flex items-center justify-center gap-2 mt-3">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $user->status === 'active' ? 'bg-green-100 text-green-700' :
                           ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5 space-y-3">
                <h3 class="text-xs font-semibold text-hali-text-secondary uppercase tracking-wider">Details</h3>
                @if($user->phone)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-phone w-4 text-center text-hali-text-secondary text-xs"></i>
                        <span class="text-hali-text-primary">{{ $user->phone }}</span>
                    </div>
                @endif
                @if($user->linkedin_url)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-brands fa-linkedin w-4 text-center text-hali-text-secondary text-xs"></i>
                        <a href="{{ $user->linkedin_url }}" target="_blank" rel="noopener"
                           class="text-primary hover:underline truncate">LinkedIn</a>
                    </div>
                @endif
                <div class="flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar w-4 text-center text-hali-text-secondary text-xs"></i>
                    <span class="text-hali-text-secondary">Joined {{ $user->created_at->format('M j, Y') }}</span>
                </div>
                @if($user->last_login_at)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-clock w-4 text-center text-hali-text-secondary text-xs"></i>
                        <span class="text-hali-text-secondary">Last login {{ $user->last_login_at->diffForHumans() }}</span>
                    </div>
                @endif
                @if($user->bio)
                    <div class="pt-2 border-t border-hali-border text-sm text-hali-text-secondary leading-relaxed">
                        {{ $user->bio }}
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5 space-y-2">
                <h3 class="text-xs font-semibold text-hali-text-secondary uppercase tracking-wider mb-3">Actions</h3>

                @if($user->status !== 'active')
                    <form method="POST" action="{{ route('admin.members.status', $user) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit"
                                class="w-full text-left text-sm px-3 py-2 rounded-lg hover:bg-green-50 text-green-700 transition-colors">
                            <i class="fa-solid fa-circle-check mr-2"></i>Activate account
                        </button>
                    </form>
                @endif

                @if($user->status !== 'suspended')
                    <form method="POST" action="{{ route('admin.members.status', $user) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit"
                                class="w-full text-left text-sm px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 transition-colors">
                            <i class="fa-solid fa-ban mr-2"></i>Suspend account
                        </button>
                    </form>
                @endif

                @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
                    <div class="border-t border-hali-border pt-2 mt-2">
                        <form method="POST" action="{{ route('admin.members.destroy', $user) }}"
                              onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full text-left text-sm px-3 py-2 rounded-lg hover:bg-red-50 text-red-700 transition-colors">
                                <i class="fa-solid fa-trash mr-2"></i>Delete member
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Right: Activity ── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Organizations --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                <h3 class="font-semibold text-hali-text-primary mb-4">Organizations</h3>
                @forelse($user->organizations as $org)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-hali-border' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-hali-text-primary">{{ $org->name }}</p>
                            <p class="text-xs text-hali-text-secondary">
                                {{ $org->pivot->role ?? 'Member' }}
                                @if($org->pivot->is_primary)
                                    <span class="ml-1 bg-primary/10 text-primary px-1.5 py-0.5 rounded text-[10px]">Primary</span>
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-hali-text-secondary">
                            {{ $org->pivot->joined_at ? \Carbon\Carbon::parse($org->pivot->joined_at)->format('M Y') : '—' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-hali-text-secondary">No organizations.</p>
                @endforelse
            </div>

            {{-- Event Registrations --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                <h3 class="font-semibold text-hali-text-primary mb-4">
                    Event Registrations
                    <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $user->eventRegistrations->count() }}</span>
                </h3>
                @forelse($user->eventRegistrations->take(10) as $reg)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-hali-border' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-hali-text-primary">{{ $reg->event->title }}</p>
                            <p class="text-xs text-hali-text-secondary">{{ $reg->event->start_datetime->format('M j, Y') }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $reg->status === 'attended' ? 'bg-green-100 text-green-700' :
                               ($reg->status === 'registered' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($reg->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-hali-text-secondary">No event registrations.</p>
                @endforelse
            </div>

            {{-- Posts --}}
            @if($user->posts->count())
                <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                    <h3 class="font-semibold text-hali-text-primary mb-4">
                        Posts
                        <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $user->posts->count() }}</span>
                    </h3>
                    @foreach($user->posts->take(5) as $post)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-hali-border' : '' }}">
                            <p class="text-sm text-hali-text-primary">{{ $post->title }}</p>
                            <span class="text-xs text-hali-text-secondary flex-shrink-0 ml-3">{{ $post->created_at->format('M j, Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Opportunities --}}
            @if($user->opportunities->count())
                <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                    <h3 class="font-semibold text-hali-text-primary mb-4">
                        Opportunities Posted
                        <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $user->opportunities->count() }}</span>
                    </h3>
                    @foreach($user->opportunities->take(5) as $opp)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-hali-border' : '' }}">
                            <p class="text-sm text-hali-text-primary">{{ $opp->title }}</p>
                            <span class="text-xs text-hali-text-secondary flex-shrink-0 ml-3">{{ $opp->created_at->format('M j, Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
