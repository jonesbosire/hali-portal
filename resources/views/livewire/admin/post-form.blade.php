<div class="space-y-5 max-w-3xl">

    <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
        <h2 class="text-sm font-semibold text-on-surface">Post Content</h2>

        <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Title *</label>
            <input wire:model="title" type="text" required
                   class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 @error('title') border-error @enderror bg-white">
            @error('title') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Excerpt</label>
            <textarea wire:model="excerpt" rows="2" placeholder="Short summary shown in listings..."
                      class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white resize-none"></textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Content *</label>
            <textarea wire:model="content" rows="16" required
                      class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 font-mono @error('content') border-error @enderror bg-white"></textarea>
            <p class="mt-1 text-xs text-on-surface-variant">HTML is supported.</p>
            @error('content') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
        <h2 class="text-sm font-semibold text-on-surface">Settings</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Type</label>
                <select wire:model="type" class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    @foreach(['update','story','blog','bulletin','resource'] as $t)
                        <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Status</label>
                <select wire:model="status" class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    @foreach(['draft','published','archived'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Published At</label>
                <input wire:model="published_at" type="datetime-local"
                       class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Categories</label>
                <select wire:model="categories" multiple
                        class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 h-[80px] bg-white">
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-on-surface-variant mt-1">Hold Ctrl/Cmd to select multiple</p>
            </div>
        </div>

        {{-- Cover image --}}
        <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Cover Image</label>
            @if($post?->cover_image && !$cover_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$post->cover_image) }}" alt="Current cover"
                         class="h-24 w-auto rounded-xl object-cover border border-surface-container-high">
                    <p class="text-xs text-on-surface-variant mt-1">Current cover image</p>
                </div>
            @endif
            @if($cover_image)
                <img src="{{ $cover_image->temporaryUrl() }}" alt="New cover preview"
                     class="h-24 w-auto rounded-xl object-cover border border-surface-container-high mb-2">
            @endif
            <label class="cursor-pointer inline-flex items-center gap-2 text-sm bg-white border border-outline-variant text-on-surface px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors font-medium">
                <i class="fa-solid fa-image text-outline"></i>
                {{ $post?->cover_image ? 'Replace Image' : 'Upload Image' }}
                <input type="file" wire:model="cover_image" accept="image/*" class="hidden">
            </label>
            <div wire:loading wire:target="cover_image" class="mt-1 text-xs text-primary">Uploading preview...</div>
            @error('cover_image') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-6 pt-1">
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input wire:model="is_featured" type="checkbox"
                       class="rounded border-outline-variant text-primary focus:ring-primary/20 h-4 w-4">
                <span class="font-medium text-on-surface">Featured post</span>
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input wire:model="is_members_only" type="checkbox"
                       class="rounded border-outline-variant text-primary focus:ring-primary/20 h-4 w-4">
                <span class="font-medium text-on-surface">Members only</span>
            </label>
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex flex-wrap gap-3">
        <button wire:click="publish" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 bg-[#0d6b62] hover:bg-[#0a5750] text-white font-bold px-6 py-2.5 rounded-xl transition-colors text-sm disabled:opacity-60">
            <span wire:loading.remove wire:target="publish">
                <i class="fa-solid fa-paper-plane mr-1"></i> Publish
            </span>
            <span wire:loading wire:target="publish">Publishing...</span>
        </button>

        <button wire:click="saveDraft" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-bold px-6 py-2.5 rounded-xl transition-colors text-sm disabled:opacity-60">
            <span wire:loading.remove wire:target="saveDraft">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Save Draft
            </span>
            <span wire:loading wire:target="saveDraft">Saving...</span>
        </button>

        <a href="{{ route('admin.posts.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant text-sm text-on-surface-variant hover:bg-surface-container-low transition-colors font-medium">
            Cancel
        </a>
    </div>

</div>
