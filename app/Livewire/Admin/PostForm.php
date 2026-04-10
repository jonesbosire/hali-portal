<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;

    public ?Post $post = null;

    public string $title          = '';
    public string $excerpt        = '';
    public string $content        = '';
    public string $type           = 'update';
    public string $status         = 'draft';
    public string $published_at   = '';
    public array  $categories     = [];
    public bool   $is_featured    = false;
    public bool   $is_members_only = false;
    public $cover_image = null;

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->post           = $post;
            $this->title          = $post->title;
            $this->excerpt        = $post->excerpt        ?? '';
            $this->content        = $post->content        ?? '';
            $this->type           = $post->type;
            $this->status         = $post->status;
            $this->published_at   = $post->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
            $this->categories     = $post->categories->pluck('id')->toArray();
            $this->is_featured    = (bool) $post->is_featured;
            $this->is_members_only = (bool) $post->is_members_only;
        } else {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title'           => ['required', 'string', 'max:255'],
            'excerpt'         => ['nullable', 'string', 'max:500'],
            'content'         => ['nullable', 'string'],
            'type'            => ['required', 'in:update,story,blog,bulletin,resource'],
            'status'          => ['required', 'in:draft,published,archived'],
            'published_at'    => ['nullable', 'date'],
            'categories'      => ['nullable', 'array'],
            'categories.*'    => ['exists:post_categories,id'],
            'is_featured'     => ['boolean'],
            'is_members_only' => ['boolean'],
            'cover_image'     => ['nullable', 'image', 'max:4096'],
        ]);

        // Handle cover image
        if ($this->cover_image) {
            if ($this->post?->cover_image) {
                Storage::disk('public')->delete($this->post->cover_image);
            }
            $validated['cover_image'] = $this->cover_image->store('posts', 'public');
            $this->cover_image = null;
        } elseif ($this->post) {
            unset($validated['cover_image']);
        }

        // Auto-set published_at when publishing for first time
        if ($validated['status'] === 'published') {
            if (!$this->post?->published_at) {
                $validated['published_at'] = now();
            }
        }

        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        if ($this->post && $this->post->exists) {
            $this->post->update($validated);
            $this->post->categories()->sync($categories);
            $message = $validated['status'] === 'published' ? 'Post published successfully!' : 'Post saved.';
        } else {
            $validated['author_id'] = auth()->id();
            $post = Post::create($validated);
            $post->categories()->sync($categories);
            $this->post = $post;
            $message = $validated['status'] === 'published' ? 'Post published successfully!' : 'Post saved as draft.';
        }

        $this->js('window.toast(\'success\', ' . json_encode($message) . ')');
    }

    public function publish(): void
    {
        $this->status = 'published';
        $this->save();
    }

    public function saveDraft(): void
    {
        $this->status = 'draft';
        $this->save();
    }

    public function render()
    {
        return view('livewire.admin.post-form', [
            'allCategories' => PostCategory::orderBy('name')->get(),
        ]);
    }
}
