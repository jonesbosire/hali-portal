<header class="flex-shrink-0 flex items-center h-16 bg-white/80 backdrop-blur-xl border-b border-outline-variant/30 shadow-sm px-6 gap-4">

    {{-- Mobile menu toggle --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="text-on-surface-variant hover:text-on-surface lg:hidden transition-colors">
        <i class="fa-solid fa-bars text-xl"></i>
    </button>

    {{-- Search --}}
    <div class="flex-1 flex">
        <div class="relative w-full max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[15px]"></i>
            <input type="search"
                   placeholder="Search partner network..."
                   class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 font-body placeholder-outline transition-all">
        </div>
    </div>

    {{-- Right actions --}}
    <div class="flex items-center gap-1 sm:gap-2">

        {{-- Notifications --}}
        <div x-data="{ open: false }" class="relative">
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            <button @click="open = !open"
                    class="relative p-2 text-on-surface-variant hover:text-primary transition-colors rounded-full hover:bg-surface-container-low">
                <i class="fa-solid fa-bell text-[20px]"></i>
                @if($unread > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full border-2 border-white"></span>
                @endif
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-surface-container-lowest rounded-2xl shadow-ambient border border-outline-variant/20 z-50"
                 style="display:none; top: calc(100% + 0.5rem);">
                <div class="p-4 flex items-center justify-between">
                    <h3 class="text-sm font-headline font-bold text-on-surface">Notifications</h3>
                    @if($unread > 0)
                        <form method="POST" action="{{ route('notifications.read', 'all') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-primary hover:text-primary-container transition-colors">Mark all read</button>
                        </form>
                    @endif
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-outline-variant/10">
                    @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $notification)
                        <a href="{{ route('notifications.index') }}"
                           class="flex items-start gap-3 px-4 py-3 hover:bg-surface-container-low transition-colors {{ $notification->read_at ? '' : 'bg-primary-fixed/30' }}">
                            <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 {{ $notification->read_at ? 'bg-outline-variant' : 'bg-primary' }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-on-surface">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $notification->data['message'] ?? '' }}</p>
                                <p class="text-[10px] text-outline mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <i class="fa-solid fa-bell-slash text-outline text-3xl block mb-2"></i>
                            <p class="text-sm text-on-surface-variant">No notifications yet</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-3 border-t border-outline-variant/10">
                    <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-primary hover:text-primary-container transition-colors py-1">
                        View all notifications
                    </a>
                </div>
            </div>
        </div>

        <div class="h-6 w-px bg-outline-variant/30 mx-1"></div>

        {{-- User avatar dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2.5 text-sm rounded-full pl-1 pr-3 py-1 hover:bg-surface-container-low transition-colors">
                <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-primary/20 flex-shrink-0">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-xs font-bold text-on-surface leading-tight max-w-32 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-outline uppercase tracking-tighter capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-outline text-[13px] hidden sm:block"></i>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-52 bg-surface-container-lowest rounded-2xl shadow-ambient border border-outline-variant/20 z-50"
                 style="display:none; top: calc(100% + 0.5rem);">
                <div class="p-2">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-on-surface hover:bg-surface-container-low rounded-xl transition-colors">
                        <i class="fa-solid fa-user-gear text-[16px] text-outline"></i>
                        My Profile
                    </a>
                    <a href="{{ route('organization.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-on-surface hover:bg-surface-container-low rounded-xl transition-colors">
                        <i class="fa-solid fa-building-columns text-[16px] text-outline"></i>
                        My Organization
                    </a>
                    <div class="border-t border-outline-variant/20 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-error hover:bg-error-container rounded-xl transition-colors">
                            <i class="fa-solid fa-right-from-bracket text-[16px]"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</header>
