<x-app-layout title="Admin Dashboard">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Admin Dashboard</h1>
        <p class="text-sm text-hali-text-secondary mt-0.5">Portal management overview</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php $widgets = [
            ['label' => 'Active Members', 'value' => $stats['total_members'], 'color' => 'text-primary bg-primary-50', 'href' => route('admin.members.index'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['label' => 'Pending Approval', 'value' => $stats['pending_members'], 'color' => 'text-yellow-700 bg-yellow-50', 'href' => route('admin.members.index', ['status' => 'pending']), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Organizations', 'value' => $stats['total_organizations'], 'color' => 'text-blue-700 bg-blue-50', 'href' => route('directory.index'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label' => 'Upcoming Events', 'value' => $stats['upcoming_events'], 'color' => 'text-purple-700 bg-purple-50', 'href' => route('admin.events.index'), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'Published Posts', 'value' => $stats['published_posts'], 'color' => 'text-teal-700 bg-teal-50', 'href' => route('admin.posts.index'), 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
            ['label' => 'Active Opps.', 'value' => $stats['active_opportunities'], 'color' => 'text-green-700 bg-green-50', 'href' => route('admin.opportunities.index'), 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['label' => 'Pending Invites', 'value' => $stats['pending_invitations'], 'color' => 'text-orange-700 bg-orange-50', 'href' => route('admin.invitations.index'), 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ]; @endphp

        @foreach($widgets as $w)
            <a href="{{ $w['href'] }}" class="bg-white rounded-xl border border-hali-border p-4 shadow-card hover:shadow-card-hover transition-shadow flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg {{ $w['color'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $w['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-hali-text-primary">{{ $w['value'] }}</p>
                    <p class="text-xs text-hali-text-secondary">{{ $w['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Quick actions --}}
    <div class="bg-white rounded-xl border border-hali-border shadow-card p-5 mb-6">
        <h3 class="text-sm font-semibold text-hali-text-primary mb-3">Quick Actions</h3>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.invitations.index') }}" class="inline-flex items-center gap-1.5 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Invite Member
            </a>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-1.5 text-sm bg-white border border-hali-border text-hali-text-primary px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Event
            </a>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-1.5 text-sm bg-white border border-hali-border text-hali-text-primary px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Publish Post
            </a>
            <a href="{{ route('admin.bulletins.create') }}" class="inline-flex items-center gap-1.5 text-sm bg-white border border-hali-border text-hali-text-primary px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                Send Bulletin
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent members --}}
        <div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-hali-border">
                <h3 class="text-sm font-semibold text-hali-text-primary">Recent Members</h3>
                <a href="{{ route('admin.members.index') }}" class="text-xs text-primary hover:underline">View all</a>
            </div>
            <div class="divide-y divide-hali-border">
                @foreach($recentMembers as $member)
                    <div class="flex items-center gap-3 p-3">
                        <img src="{{ $member->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-hali-text-primary truncate">{{ $member->name }}</p>
                            <p class="text-xs text-hali-text-secondary">{{ $member->organizations->first()?->name ?? '—' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Upcoming events --}}
        <div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-hali-border">
                <h3 class="text-sm font-semibold text-hali-text-primary">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="text-xs text-primary hover:underline">View all</a>
            </div>
            <div class="divide-y divide-hali-border">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-center gap-3 p-3">
                        <div class="text-center w-10 flex-shrink-0">
                            <div class="text-xs font-bold text-white bg-primary rounded-t py-0.5">{{ $event->start_datetime->format('M') }}</div>
                            <div class="text-sm font-bold text-primary bg-primary-50 rounded-b border border-primary-100 border-t-0">{{ $event->start_datetime->format('d') }}</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('admin.events.show', $event) }}" class="text-sm font-medium text-hali-text-primary hover:text-primary truncate block">{{ $event->title }}</a>
                            <p class="text-xs text-hali-text-secondary">{{ $event->registrations->count() }} registered · {{ ucfirst($event->location_type) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-hali-text-secondary">No upcoming events</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
