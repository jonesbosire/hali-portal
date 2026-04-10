<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::withTrashed()
            ->select(['id', 'title', 'type', 'status', 'is_featured', 'is_members_only', 'published_at', 'created_at', 'deleted_at', 'author_id'])
            ->with(['author:id,name', 'categories:id,name,slug'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::orderBy('name')->get();
        return view('admin.posts.form', ['post' => new Post(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'type' => 'required|in:update,story,blog,bulletin,resource',
            'cover_image' => 'nullable|image|max:4096',
            'is_members_only' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:post_categories,id',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $validated['author_id'] = auth()->id();

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $post = Post::create($validated);

        if ($categories) {
            $post->categories()->sync($categories);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::orderBy('name')->get();
        $post->load('categories');
        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'type' => 'required|in:update,story,blog,bulletin,resource',
            'cover_image' => 'nullable|image|max:4096',
            'is_members_only' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:post_categories,id',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $post->update($validated);
        $post->categories()->sync($categories);

        return back()->with('success', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted.');
    }

    public function show(Post $post)
    {
        return redirect()->route('posts.show', $post);
    }
}
