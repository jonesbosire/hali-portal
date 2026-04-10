<x-app-layout :title="$post->title">
    <div class="max-w-3xl">
        <nav class="text-xs text-hali-text-secondary mb-4 flex items-center gap-1.5">
            <a href="{{ route('posts.index') }}" class="hover:text-primary">Stories & Updates</a>
            <span>/</span>
            <span class="text-hali-text-primary truncate max-w-xs">{{ $post->title }}</span>
        </nav>

        <div class="bg-white rounded-2xl border border-hali-border shadow-card overflow-hidden">
            @if($post->cover_image)
                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}"
                     class="w-full max-h-64 object-cover">
            @endif
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                        {{ $post->type === 'update' ? 'bg-blue-100 text-blue-700' : ($post->type === 'story' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($post->type) }}
                    </span>
                    @foreach($post->categories as $cat)
                        <span class="text-xs px-2 py-0.5 rounded-full" style="background: {{ $cat->color_hex }}22; color: {{ $cat->color_hex }}">{{ $cat->name }}</span>
                    @endforeach
                    @if($post->is_members_only)
                        <span class="text-xs flex items-center gap-0.5 text-gray-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            Members only
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-hali-text-primary mb-3">{{ $post->title }}</h1>

                {{-- Author --}}
                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-hali-border">
                    <img src="{{ $post->author?->avatar_url ?? 'https://ui-avatars.com/api/?name=HALI&background=1A7A8A&color=fff' }}"
                         alt="" class="w-8 h-8 rounded-full object-cover">
                    <div>
                        <p class="text-sm font-medium text-hali-text-primary">{{ $post->author?->name ?? 'HALI Secretariat' }}</p>
                        <p class="text-xs text-hali-text-secondary">{{ $post->published_at?->format('F j, Y') }} · {{ number_format($post->views_count) }} views</p>
                    </div>
                </div>

                {{-- Content --}}
                @if($post->excerpt)
                    <p class="text-base text-hali-text-secondary font-medium mb-4 italic">{{ $post->excerpt }}</p>
                @endif

                <div class="prose prose-sm max-w-none text-hali-text-secondary">
                    {!! $post->content !!}
                </div>
            </div>
        </div>

        {{-- Related posts --}}
        @if($related->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-sm font-semibold text-hali-text-primary mb-4">Related Posts</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($related as $rel)
                        <a href="{{ route('posts.show', $rel) }}"
                           class="bg-white rounded-xl border border-hali-border p-4 hover:shadow-card-hover transition-shadow">
                            <p class="text-xs font-semibold text-hali-text-primary hover:text-primary line-clamp-2">{{ $rel->title }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $rel->published_at?->format('M j, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
