<x-app-layout title="{{ $event->title }} — Admin">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.events.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Events</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary truncate">{{ $event->title }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($event->type) }}</span>
                            @if($event->status === 'published')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Published</span>
                            @elseif($event->status === 'draft')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Draft</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-600">Canceled</span>
                            @endif
                            @if($event->is_members_only)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-primary-50 text-primary">Members Only</span>
                            @endif
                        </div>
                        <h2 class="text-lg font-bold text-hali-text-primary">{{ $event->title }}</h2>
                    </div>
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="text-sm text-primary hover:underline flex-shrink-0">Edit</a>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm mb-5">
                    <div>
                        <dt class="text-xs text-hali-text-secondary">Start</dt>
                        <dd class="font-medium text-hali-text-primary">{{ $event->start_datetime->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-hali-text-secondary">End</dt>
                        <dd class="font-medium text-hali-text-primary">{{ $event->end_datetime?->format('M j, Y g:i A') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-hali-text-secondary">Location</dt>
                        <dd class="font-medium text-hali-text-primary">
                            {{ $event->venue_name ?? ucfirst($event->location_type) }}
                            @if($event->venue_address) <span class="text-hali-text-secondary font-normal block text-xs">{{ $event->venue_address }}</span> @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-hali-text-secondary">Registration Closes</dt>
                        <dd class="font-medium text-hali-text-primary">{{ $event->registration_closes_at?->format('M j, Y') ?? '—' }}</dd>
                    </div>
                </dl>

                @if($event->description)
                    <div class="prose prose-sm max-w-none text-hali-text-secondary border-t border-hali-border pt-4">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                @endif
            </div>

            {{-- Attendees --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card overflow-hidden">
                <div class="flex items-center justify-between p-5 border-b border-hali-border">
                    <h3 class="text-sm font-semibold text-hali-text-primary">
                        Attendees ({{ $event->registrations->count() }})
                    </h3>
                    <a href="{{ route('admin.events.export', $event) }}"
                       class="text-xs text-primary hover:underline">Export CSV</a>
                </div>
                @if($event->registrations->isEmpty())
                    <div class="p-8 text-center text-sm text-hali-text-secondary">No registrations yet</div>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-hali-border">
                            <tr>
                                <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Member</th>
                                <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3 hidden sm:table-cell">Registered</th>
                                <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hali-border">
                            @foreach($event->registrations as $reg)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $reg->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover" alt="">
                                            <div>
                                                <p class="font-medium text-hali-text-primary text-xs">{{ $reg->user->name }}</p>
                                                <p class="text-xs text-hali-text-secondary">{{ $reg->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-hali-text-secondary hidden sm:table-cell">
                                        {{ $reg->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($reg->attended_at)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Attended</span>
                                        @else
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Registered</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        @if(!$reg->attended_at)
                                            <form method="POST" action="{{ route('admin.events.attend', [$event, $reg]) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-primary hover:underline">Mark Attended</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- links omitted; registrations loaded as eager collection --}}
                @endif
            </div>

            {{-- ── Event Program / Agenda ── --}}
            <div class="bg-white rounded-2xl border border-hali-border shadow-card overflow-hidden">
                <div class="flex items-center justify-between p-5 border-b border-hali-border">
                    <h3 class="text-sm font-semibold text-hali-text-primary">
                        Event Program / Agenda
                        <span class="ml-2 text-xs font-normal text-hali-text-secondary">(visible to all attendees)</span>
                    </h3>
                </div>

                {{-- Existing items --}}
                @if($event->programs->isEmpty())
                    <div class="p-6 text-center text-sm text-hali-text-secondary">No program items yet. Add the first session below.</div>
                @else
                    <div class="divide-y divide-hali-border">
                        @foreach($event->programs as $item)
                            <div class="flex items-start justify-between gap-4 px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-xs font-bold text-primary">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        @if($item->time_range)
                                            <p class="text-xs text-hali-text-secondary mb-0.5">{{ $item->time_range }}</p>
                                        @endif
                                        <p class="text-sm font-semibold text-hali-text-primary">{{ $item->title }}</p>
                                        @if($item->speaker)
                                            <p class="text-xs text-hali-text-secondary mt-0.5">
                                                {{ $item->speaker }}
                                                @if($item->speaker_title) · {{ $item->speaker_title }} @endif
                                            </p>
                                        @endif
                                        @if($item->description)
                                            <p class="text-xs text-hali-text-secondary mt-1">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.events.programs.destroy', [$event, $item]) }}" class="flex-shrink-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove this program item?')"
                                            class="text-xs text-red-400 hover:text-red-600 transition-colors">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add new item form --}}
                <div class="border-t border-hali-border p-5 bg-gray-50/50">
                    <p class="text-xs font-semibold text-hali-text-secondary uppercase tracking-wide mb-3">Add Program Item</p>
                    <form method="POST" action="{{ route('admin.events.programs.store', $event) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <input type="text" name="title" placeholder="Session title *" required
                                       value="{{ old('title') }}"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="text" name="speaker" placeholder="Speaker name"
                                       value="{{ old('speaker') }}"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            </div>
                            <div>
                                <input type="text" name="speaker_title" placeholder="Speaker title / org"
                                       value="{{ old('speaker_title') }}"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs text-hali-text-secondary mb-1">Start time</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs text-hali-text-secondary mb-1">End time</label>
                                <input type="time" name="end_time" value="{{ old('end_time') }}"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            </div>
                            <div class="sm:col-span-2">
                                <textarea name="description" rows="2" placeholder="Brief description (optional)"
                                          class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white resize-none">{{ old('description') }}</textarea>
                            </div>
                            <div>
                                <input type="number" name="sort_order" placeholder="Order (0, 1, 2…)"
                                       value="{{ old('sort_order') }}" min="0"
                                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            </div>
                        </div>
                        <button type="submit"
                                class="text-sm bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                            + Add to Program
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5">
                <h3 class="text-sm font-semibold text-hali-text-primary mb-4">Capacity</h3>
                @php $count = $event->registrations->count(); $max = $event->max_attendees; @endphp
                <div class="text-center mb-3">
                    <p class="text-3xl font-bold text-primary">{{ $count }}</p>
                    <p class="text-xs text-hali-text-secondary">{{ $max ? "of {$max} spots filled" : 'registered' }}</p>
                </div>
                @if($max)
                    @php $pct = min(100, round($count / $max * 100)); @endphp
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-primary') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-hali-text-secondary mt-1 text-center">{{ $max - $count }} spots remaining</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5">
                <h3 class="text-sm font-semibold text-hali-text-primary mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="block w-full text-center text-sm bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition-colors">
                        Edit Event
                    </a>
                    <a href="{{ route('admin.events.export', $event) }}"
                       class="block w-full text-center text-sm border border-hali-border text-hali-text-primary py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        Export Attendees CSV
                    </a>
                    <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                       class="block w-full text-center text-sm border border-hali-border text-hali-text-primary py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        View Public Page ↗
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
