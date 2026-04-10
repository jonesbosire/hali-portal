<x-app-layout title="{{ isset($post) && $post->exists ? 'Edit Post' : 'New Post' }} — Admin">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
        <a href="{{ route('admin.posts.index') }}"
           class="inline-flex items-center gap-1.5 text-on-surface-variant hover:text-primary text-sm font-medium transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Posts
        </a>
        <span class="text-outline-variant hidden sm:block">/</span>
        <h1 class="font-headline text-xl font-bold text-on-surface">
            {{ isset($post) && $post->exists ? 'Edit Post' : 'New Post' }}
        </h1>
    </div>

    <livewire:admin.post-form :post="isset($post) && $post->exists ? $post : null" />
</x-app-layout>
