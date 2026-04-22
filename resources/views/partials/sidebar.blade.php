<nav class="flex flex-col w-[220px] min-h-screen bg-[#fdf6ef] border-r border-[#edd5be] overflow-y-auto shadow-sidebar" aria-label="Sidebar">

    {{-- Logo --}}
    <div class="px-4 pt-5 pb-4 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/hali-logo.png') }}"
                 alt="HALI Access Network"
                 class="w-full max-w-[160px] h-auto object-contain">
        </a>
    </div>

    {{-- Main navigation --}}
    <div class="flex-1 flex flex-col py-2 overflow-y-auto">
        <div class="space-y-0.5 px-3">

            @php
                $navItems = [
                    ['route' => 'dashboard',          'label' => 'Dashboard',         'icon' => 'fa-solid fa-gauge-high',    'color' => '#7c3d1f'],
                    ['route' => 'directory.index',    'label' => 'Member Directory',  'icon' => 'fa-solid fa-users',         'color' => '#1d4ed8'],
                    ['route' => 'events.index',       'label' => 'Events',            'icon' => 'fa-regular fa-calendar',    'color' => '#7c3a8b'],
                    ['route' => 'posts.index',        'label' => 'Stories & Updates', 'icon' => 'fa-solid fa-bullhorn',      'color' => '#0d6b62'],
                    ['route' => 'opportunities.index','label' => 'Opportunities',     'icon' => 'fa-regular fa-lightbulb',   'color' => '#b45309'],
                    ['route' => 'resources.index',    'label' => 'Resources',         'icon' => 'fa-regular fa-folder-open', 'color' => '#0d6b62'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']) || request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm tracking-tight transition-all duration-150
                          {{ $active
                             ? 'bg-white shadow-card text-[#3d1500] font-medium'
                             : 'font-normal text-[#5c3520] hover:bg-[#f5e8d8] hover:text-[#3d1500]' }}">
                    <i class="{{ $item['icon'] }} w-4 text-center text-[14px]"
                       style="color: {{ $active ? $item['color'] : 'currentColor' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            {{-- My Workspace --}}
            <div class="mt-4 pt-3 border-t border-[#edd5be]">
                <p class="px-3 text-[10px] font-semibold text-[#9d7060] uppercase tracking-widest mb-1.5">My Workspace</p>

                @php $orgActive = request()->routeIs('organization.*') || request()->routeIs('billing.*'); @endphp
                <div x-data="{ orgOpen: {{ $orgActive ? 'true' : 'false' }} }">
                    <button @click="orgOpen = !orgOpen"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm tracking-tight transition-all duration-150
                                   {{ $orgActive ? 'bg-white shadow-card text-[#3d1500] font-medium' : 'font-normal text-[#5c3520] hover:bg-[#f5e8d8] hover:text-[#3d1500]' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-building-columns w-4 text-center text-[14px]"></i>
                            My Organization
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200"
                           :class="orgOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="orgOpen" class="mt-0.5 ml-10 space-y-0.5">
                        <a href="{{ route('organization.edit') }}"
                           class="block px-3 py-1.5 text-xs rounded-lg transition-colors
                                  {{ request()->routeIs('organization.*') ? 'text-[#7c3d1f] font-medium' : 'font-normal text-[#7a5848] hover:text-[#3d1500]' }}">
                            Profile & Team
                        </a>
                        <a href="{{ route('billing.index') }}"
                           class="block px-3 py-1.5 text-xs rounded-lg transition-colors
                                  {{ request()->routeIs('billing.*') ? 'text-[#7c3d1f] font-medium' : 'font-normal text-[#7a5848] hover:text-[#3d1500]' }}">
                            Billing
                        </a>
                    </div>
                </div>

                {{-- Notifications --}}
                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                <a href="{{ route('notifications.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm tracking-tight transition-all duration-150
                          {{ request()->routeIs('notifications.*') ? 'bg-white shadow-card text-[#3d1500] font-medium' : 'font-normal text-[#5c3520] hover:bg-[#f5e8d8] hover:text-[#3d1500]' }}">
                    <i class="fa-regular fa-bell w-4 text-center text-[14px]"></i>
                    Notifications
                    @if($unread > 0)
                        <span class="ml-auto bg-[#cc9933] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-5 text-center leading-tight">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm tracking-tight transition-all duration-150
                          {{ request()->routeIs('profile.*') ? 'bg-white shadow-card text-[#3d1500] font-medium' : 'font-normal text-[#5c3520] hover:bg-[#f5e8d8] hover:text-[#3d1500]' }}">
                    <i class="fa-solid fa-user-gear w-4 text-center text-[14px]"></i>
                    My Profile
                </a>
            </div>

            {{-- Admin Section --}}
            @if(auth()->user()->isAdmin())
                <div class="mt-4 pt-3 border-t border-[#edd5be]">
                    <p class="px-3 text-[10px] font-semibold text-[#9d7060] uppercase tracking-widest mb-1.5">Administration</p>
                    @php $adminItems = [
                        ['route' => 'admin.dashboard',         'label' => 'Admin Dashboard',  'icon' => 'fa-solid fa-chart-line',         'color' => '#1d4ed8'],
                        ['route' => 'admin.members.index',     'label' => 'Members',           'icon' => 'fa-solid fa-users-gear',         'color' => '#7c3d1f'],
                        ['route' => 'admin.events.index',      'label' => 'Manage Events',     'icon' => 'fa-regular fa-calendar-plus',    'color' => '#7c3a8b'],
                        ['route' => 'admin.posts.index',       'label' => 'Manage Content',    'icon' => 'fa-solid fa-pen-to-square',      'color' => '#0d6b62'],
                        ['route' => 'admin.invitations.index', 'label' => 'Invitations',       'icon' => 'fa-regular fa-envelope-open',    'color' => '#b45309'],
                    ]; @endphp
                    @foreach($adminItems as $item)
                        @php $active = request()->routeIs($item['route']) || str_starts_with(request()->route()?->getName() ?? '', str_replace('.index','',$item['route'])); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm tracking-tight transition-all duration-150
                                  {{ $active ? 'bg-white shadow-card text-[#3d1500] font-medium' : 'font-normal text-[#5c3520] hover:bg-[#f5e8d8] hover:text-[#3d1500]' }}">
                            <i class="{{ $item['icon'] }} w-4 text-center text-[14px]"
                               style="color: {{ $active ? $item['color'] : 'currentColor' }}"></i>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- User footer --}}
    <div class="flex-shrink-0 px-4 py-4 border-t border-[#edd5be] bg-[#fdf6ef]">
        <div class="flex items-center gap-3">

            {{-- Avatar with unread dot --}}
            <div class="relative w-8 h-8 flex-shrink-0 rounded-full overflow-hidden ring-2 ring-[#edd5be]">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                     class="w-full h-full object-cover">
                @if($unread > 0)
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full ring-1 ring-[#fdf6ef]"></span>
                @endif
            </div>

            <div class="flex-1 min-w-0 overflow-hidden">
                <p class="text-[#3d1500] text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                @if(auth()->user()->isFriend())
                    <span class="inline-block text-[9px] font-semibold bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full leading-tight">Friend Organisation</span>
                @else
                    <p class="text-[#9d7060] text-[10px] truncate capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[#9d7060] hover:text-[#7c3d1f] transition-colors" title="Sign out">
                    <i class="fa-solid fa-right-from-bracket text-[14px]"></i>
                </button>
            </form>
        </div>
    </div>

</nav>
