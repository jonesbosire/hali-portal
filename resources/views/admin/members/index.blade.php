<x-app-layout title="Members — Admin">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Members</h1>
        <a href="{{ route('admin.invitations.index') }}"
           class="inline-flex items-center gap-1.5 text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
            + Invite Member
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-hali-border p-4 mb-5 flex flex-wrap gap-3 shadow-card">
        <div class="relative flex-1 min-w-48">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email..."
                   class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <select name="role" class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary">
            <option value="">All roles</option>
            @foreach(['super_admin','secretariat','member','friend'] as $r)
                <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
            @endforeach
        </select>
        <select name="status" class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary">
            <option value="">All statuses</option>
            @foreach(['active','pending','suspended'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="text-sm bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-hali-border shadow-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-hali-border">
                <tr>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Member</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden md:table-cell">Organization</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden sm:table-cell">Role</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden lg:table-cell">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hali-border">
                @forelse($users as $member)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $member->avatar_url }}" alt="" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                                <div>
                                    <p class="font-medium text-hali-text-primary">{{ $member->name }}</p>
                                    <p class="text-xs text-hali-text-secondary">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-hali-text-secondary hidden md:table-cell">
                            {{ $member->organizations->first()?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ ucfirst(str_replace('_',' ',$member->role)) }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : ($member->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-hali-text-secondary hidden lg:table-cell">{{ $member->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-hali-text-secondary hover:text-primary p-1 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-1 w-36 bg-white border border-hali-border rounded-lg shadow-card-hover z-10"
                                     style="display:none">
                                    @if($member->status !== 'active')
                                        <form method="POST" action="{{ route('admin.members.status', $member) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-50 text-green-700">Activate</button>
                                        </form>
                                    @endif
                                    @if($member->status !== 'suspended')
                                        <form method="POST" action="{{ route('admin.members.status', $member) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-50 text-red-600">Suspend</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-hali-text-secondary">No members found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-hali-border">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
