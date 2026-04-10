<x-app-layout title="Opportunities — Admin">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Opportunities</h1>
        <a href="{{ route('admin.opportunities.create') }}"
           class="inline-flex items-center gap-1.5 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
            + Post Opportunity
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-hali-border p-4 mb-5 flex flex-wrap gap-3 shadow-card">
        <div class="relative flex-1 min-w-48">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search opportunities..."
                   class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <select name="type" class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary">
            <option value="">All types</option>
            @foreach(['job','fellowship','scholarship','grant','conference','other'] as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <select name="status" class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary">
            <option value="">All statuses</option>
            @foreach(['active','expired','draft'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="text-sm bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-hali-border">
                <tr>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Opportunity</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden sm:table-cell">Type</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden md:table-cell">Posted By</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden lg:table-cell">Deadline</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hali-border">
                @forelse($opportunities as $opp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-hali-text-primary">{{ $opp->title }}</p>
                            <p class="text-xs text-hali-text-secondary">{{ $opp->organization }}</p>
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($opp->type) }}</span>
                        </td>
                        <td class="px-5 py-3 text-xs text-hali-text-secondary hidden md:table-cell">
                            {{ $opp->postedBy?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            @if($opp->status === 'active')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Active</span>
                            @elseif($opp->status === 'draft')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Draft</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Expired</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs hidden lg:table-cell">
                            @if($opp->deadline_at)
                                <span class="{{ $opp->deadline_at->isPast() ? 'text-red-500' : 'text-hali-text-secondary' }}">
                                    {{ $opp->deadline_at->format('M j, Y') }}
                                </span>
                            @else
                                <span class="text-hali-text-secondary">Open</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.opportunities.edit', $opp) }}" class="text-xs text-primary hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.opportunities.destroy', $opp) }}" class="inline"
                                      onsubmit="return confirm('Delete this opportunity?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-hali-text-secondary">No opportunities found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-hali-border">
            {{ $opportunities->links() }}
        </div>
    </div>
</x-app-layout>
