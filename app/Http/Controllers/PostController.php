<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()
            ->fromActiveAuthors()
            ->with(['author', 'organization', 'categories'])
            ->orderByDesc('published_at');

        if ($type = $request->get('type')) {
            $query->ofType($type);
        }

        if ($category = $request->get('category')) {
            $query->whereHas('categories', fn($q) => $q->where('slug', $category));
        }

        // Non-members see public posts only
        if (!auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            $query->public();
        }

        $posts = $query->paginate(12)->withQueryString();
        $featured = Post::published()->featured()
            ->select(['id', 'title', 'excerpt', 'slug', 'cover_image', 'type', 'is_members_only', 'published_at', 'author_id', 'organization_id'])
            ->with(['author:id,name', 'organization:id,name'])
            ->orderByDesc('published_at')
            ->first();
        $categories = PostCategory::select(['id', 'name', 'slug'])
            ->whereHas('posts', fn($q) => $q->published())
            ->get();

        return view('posts.index', compact('posts', 'featured', 'categories'));
    }

    public function show(Post $post)
    {
        abort_if($post->status !== 'published', 404);

        // Gate members-only content
        if ($post->is_members_only && !auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            abort(403, 'This content is for members only.');
        }

        $post->incrementViews();
        $post->load('author', 'organization', 'categories');

        $related = Post::published()
            ->select(['id', 'title', 'excerpt', 'slug', 'cover_image', 'type', 'published_at', 'author_id'])
            ->with(['author:id,name'])
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn($q) => $q->whereIn('post_categories.id', $post->categories->pluck('id')))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }
}
