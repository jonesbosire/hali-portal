<x-app-layout title="Notifications">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Notifications</h1>
        @if($notifications->where('read_at', null)->count() > 0)
            <form method="POST" action="{{ route('notifications.read', 'all') }}">
                @csrf
                <button type="submit" class="text-sm text-primary hover:underline">Mark all as read</button>
            </form>
        @endif
    </div>

    <div class="max-w-2xl">
        @if($notifications->isEmpty())
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-12 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-hali-text-secondary text-sm">You're all caught up! No notifications yet.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-hali-border shadow-card divide-y divide-hali-border overflow-hidden">
                @foreach($notifications as $notification)
                    @php $isUnread = is_null($notification->read_at); @endphp
                    <div class="flex items-start gap-4 p-5 {{ $isUnread ? 'bg-primary-50/30' : '' }} hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center
                            {{ $notification->data['type'] ?? 'info' === 'success' ? 'bg-green-100' : ($notification->data['type'] ?? 'info' === 'warning' ? 'bg-yellow-100' : 'bg-primary/10') }}">
                            @if(($notification->data['icon'] ?? '') === 'event')
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif(($notification->data['icon'] ?? '') === 'post')
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-medium text-hali-text-primary">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 bg-accent rounded-full ml-1 align-middle"></span>
                                        @endif
                                    </p>
                                    @if(isset($notification->data['message']))
                                        <p class="text-xs text-hali-text-secondary mt-0.5">{{ $notification->data['message'] }}</p>
                                    @endif
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="text-xs text-primary hover:underline mt-1 inline-block">
                                            {{ $notification->data['action_text'] ?? 'View' }} →
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs text-hali-text-secondary whitespace-nowrap">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if($isUnread)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Mark as read"
                                                    class="text-hali-text-secondary hover:text-primary transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
