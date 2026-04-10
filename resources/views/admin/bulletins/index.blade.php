<x-app-layout title="Bulletins — Admin">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Member Bulletins</h1>
        <a href="{{ route('admin.bulletins.create') }}"
           class="inline-flex items-center gap-1.5 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
            + Compose Bulletin
        </a>
    </div>

    <div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-hali-border">
                <tr>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Subject</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden sm:table-cell">Audience</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden md:table-cell">Sent</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hali-border">
                @forelse($bulletins as $bulletin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-hali-text-primary">{{ $bulletin->subject }}</p>
                            <p class="text-xs text-hali-text-secondary truncate max-w-xs">{{ Str::limit(strip_tags($bulletin->body), 80) }}</p>
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $bulletin->audience ?? 'all')) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($bulletin->sent_at)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Sent</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-hali-text-secondary hidden md:table-cell">
                            {{ $bulletin->sent_at?->format('M j, Y g:i A') ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.bulletins.show', $bulletin) }}" class="text-xs text-primary hover:underline">View</a>
                                @if(!$bulletin->sent_at)
                                    <a href="{{ route('admin.bulletins.edit', $bulletin) }}" class="text-xs text-hali-text-secondary hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.bulletins.send', $bulletin) }}" class="inline"
                                          onsubmit="return confirm('Send this bulletin to all recipients now?')">
                                        @csrf
                                        <button type="submit" class="text-xs text-accent font-medium hover:underline">Send Now</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-hali-text-secondary">No bulletins sent yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-hali-border">
            {{ $bulletins->links() }}
        </div>
    </div>
</x-app-layout>
