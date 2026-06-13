<?php

namespace App\Http\Controllers\NewStore;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\CacheService;

class BlogController extends Controller
{
    public function index(CacheService $cache)
    {
        $featured = BlogPost::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'thumbnail', 'excerpt', 'content', 'published_at']);

        $posts = BlogPost::published()
            ->latest('published_at')
            ->paginate(9, ['id', 'title', 'slug', 'thumbnail', 'excerpt', 'content', 'published_at']);

        return view('new-store.blog.index', compact('posts', 'featured'));
    }

    public function show($slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->firstOrFail(['id', 'title', 'slug', 'thumbnail', 'content', 'published_at']);

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'thumbnail', 'excerpt', 'content', 'published_at']);

        return view('new-store.blog.show', compact('post', 'related'));
    }
}
