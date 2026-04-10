<x-app-layout title="Stories & Updates">

    {{-- ── Page header ── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="font-headline text-3xl font-bold text-on-surface tracking-tight">Stories & Updates</h1>
            <p class="text-on-surface-variant mt-1">News, announcements and impact stories from the HALI network</p>
        </div>
    </div>

    {{-- ── Type filter tabs ── --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('posts.index') }}"
           class="px-4 py-2 rounded-full text-sm font-bold transition-all
                  {{ !request('type') ? 'bg-primary text-white shadow-md' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/30 hover:border-primary hover:text-primary' }}">
            All
        </a>
        @foreach(['update' => 'Secretariat Updates', 'story' => 'Member Stories', 'blog' => 'Blog', 'bulletin' => 'Bulletins'] as $type => $label)
            <a href="{{ route('posts.index', ['type' => $type]) }}"
               class="px-4 py-2 rounded-full text-sm font-bold transition-all
                      {{ request('type') === $type ? 'bg-primary text-white shadow-md' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/30 hover:border-primary hover:text-primary' }}">
                {{ $label }}
            </a>
        @endforeach
        @foreach($categories as $cat)
            <a href="{{ route('posts.index', ['category' => $cat->slug]) }}"
               class="px-4 py-2 rounded-full text-sm font-bold transition-all
                      {{ request('category') === $cat->slug ? 'bg-secondary-container text-on-secondary-container shadow-md' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/30 hover:border-secondary-container hover:text-secondary' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    {{-- ── Featured post ── --}}
    @if($featured && !request('type') && !request('category'))
        <a href="{{ route('posts.show', $featured) }}" class="block group mb-10">
            <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-primary to-primary-container min-h-[320px] flex items-end shadow-2xl shadow-primary/10">
                @if($featured->cover_image)
                    <img src="{{ route('files.serve', ['path' => $featured->cover_image]) }}"
                         alt="{{ $featured->title }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="relative z-10 p-8 md:p-12 space-y-3 w-full">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary-container text-on-secondary-container font-bold text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-star text-[12px]"></i>
                            Featured
                        </span>
                        <span class="px-3 py-1.5 rounded-full bg-white/20 backdrop-blur text-white text-xs font-bold uppercase tracking-widest">
                            {{ ucfirst($featured->type) }}
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-headline font-bold text-white leading-tight max-w-3xl group-hover:text-primary-fixed transition-colors">
                        {{ $featured->title }}
                    </h2>
                    @if($featured->excerpt)
                        <p class="text-white/80 text-base max-w-2xl leading-relaxed line-clamp-2">{{ $featured->excerpt }}</p>
                    @endif
                    <div class="flex items-center gap-4 pt-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-primary-fixed">
                                @if($featured->author?->avatar)
                                    <img src="{{ $featured->author->avatar_url }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="text-white/80 text-sm font-medium">{{ $featured->author?->name ?? 'HALI Secretariat' }}</span>
                        </div>
                        <span class="text-white/40">·</span>
                        <span class="text-white/60 text-sm">{{ $featured->published_at?->format('F j, Y') }}</span>
                        <span class="ml-auto text-sm font-bold text-white flex items-center gap-1 group-hover:gap-2 transition-all">
                            Read Story
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    @endif

    {{-- ── Posts grid ── --}}
    @if($posts->isEmpty())
        <div class="bg-surface-container-lowest rounded-2xl p-16 text-center">
            <i class="fa-solid fa-newspaper text-outline text-5xl block mb-3"></i>
            <p class="text-on-surface-variant font-medium">No posts found</p>
            @if(request('type') || request('category'))
                <a href="{{ route('posts.index') }}" class="mt-3 inline-block text-primary text-sm font-bold hover:underline">View all posts</a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post) }}"
                   class="group bg-surface-container-lowest rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-primary/5 transition-all duration-300 flex flex-col">

                    {{-- Cover --}}
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-primary/20 to-primary-fixed/30">
                        @if($post->cover_image)
                            <img src="{{ route('files.serve', ['path' => $post->cover_image]) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-newspaper text-primary/20 text-6xl"></i>
                            </div>
                        @endif
                        {{-- Members-only badge --}}
                        @if($post->is_members_only)
                            <div class="absolute top-4 right-4">
                                <span class="flex items-center gap-1 px-2 py-1 bg-black/50 backdrop-blur text-white text-[10px] font-bold rounded-full">
                                    <i class="fa-solid fa-lock text-[11px]"></i>
                                    Members
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="p-6 flex flex-col flex-1">
                        {{-- Type + category chips --}}
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ match($post->type) {
                                    'update'   => 'bg-primary/10 text-primary',
                                    'story'    => 'bg-primary-fixed/50 text-on-primary-fixed-variant',
                                    'bulletin' => 'bg-secondary-container/30 text-secondary',
                                    default    => 'bg-surface-container-high text-on-surface-variant'
                                } }}">
                                {{ ucfirst($post->type) }}
                            </span>
                            @foreach($post->categories->take(1) as $cat)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-surface-container text-on-surface-variant">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>

                        <h3 class="font-headline font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-2">
                            {{ $post->title }}
                        </h3>
                        @if($post->excerpt)
                            <p class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                        @endif

                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-outline-variant/10">
                            <div class="flex items-center gap-2">
                                @if($post->author?->avatar)
                                    <img src="{{ $post->author->avatar_url }}" alt=""
                                         class="w-6 h-6 rounded-full object-cover">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center">
                                        <i class="fa-solid fa-user text-primary text-[12px]"></i>
                                    </div>
                                @endif
                                <span class="text-xs text-on-surface-variant truncate max-w-24">{{ $post->author?->name ?? 'HALI Secretariat' }}</span>
                            </div>
                            <span class="text-[10px] text-outline">{{ $post->published_at?->format('M j, Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $posts->links() }}
    @endif

</x-app-layout>
