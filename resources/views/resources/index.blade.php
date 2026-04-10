<x-app-layout title="Resources">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-hali-text-primary">Resource Library</h1>
    </div>

    {{-- Filter bar --}}
    <form method="GET" class="bg-white rounded-xl border border-hali-border p-4 mb-6 flex flex-wrap gap-3 items-center shadow-card">
        <div class="relative flex-1 min-w-48">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search resources..."
                   class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <select name="type" class="text-sm border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary">
            <option value="">All types</option>
            @foreach(['document','link','video','template'] as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}s</option>
            @endforeach
        </select>
        <button type="submit" class="text-sm bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">Filter</button>
    </form>

    @if($resources->isEmpty())
        <div class="bg-white rounded-xl border border-hali-border p-12 text-center shadow-card">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <p class="text-hali-text-secondary">No resources found</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            @foreach($resources as $resource)
                @php
                    $iconPath = match($resource->type) {
                        'video' => 'M15 10l4.553-2.069A1 1 0 0121 8.847v6.306a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                        'link' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
                        'template' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        default => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    };
                    $bgColor = match($resource->type) {
                        'video' => 'bg-red-50 text-red-500',
                        'link' => 'bg-blue-50 text-blue-500',
                        'template' => 'bg-green-50 text-green-500',
                        default => 'bg-primary-50 text-primary',
                    };
                @endphp
                <div class="bg-white rounded-xl border border-hali-border shadow-card hover:shadow-card-hover transition-all p-4 flex flex-col">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="w-10 h-10 rounded-lg {{ $bgColor }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-hali-text-primary line-clamp-2">{{ $resource->title }}</h3>
                            @if($resource->description)
                                <p class="text-xs text-hali-text-secondary mt-1 line-clamp-2">{{ $resource->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-hali-border flex items-center justify-between">
                        <div class="flex items-center gap-1 text-xs text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ $resource->download_count }} {{ $resource->type === 'link' ? 'visits' : 'downloads' }}
                        </div>
                        <a href="{{ route('resources.download', $resource) }}"
                           class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
                            {{ $resource->type === 'link' ? 'Open' : 'Download' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $resources->links() }}
    @endif
</x-app-layout>
