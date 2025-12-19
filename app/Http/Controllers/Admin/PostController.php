<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\BlogPost;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $items = BlogPost::orderByDesc('published_at')->paginate(20)->withQueryString();
        return Inertia::render('admin/PostsIndex', ['items' => $items]);
    }

    public function create()
    {
        return Inertia::render('admin/posts/Form', ['item' => new BlogPost()]);
    }

    public function store(PostRequest $request)
    {
        BlogPost::create($request->validated());
        return redirect()->route('admin.posts.index')->with('success', 'Article créé');
    }

    public function edit(BlogPost $post)
    {
        return Inertia::render('admin/posts/Form', ['item' => $post]);
    }

    public function update(PostRequest $request, BlogPost $post)
    {
        $post->update($request->validated());
        return redirect()->route('admin.posts.index')->with('success', 'Article mis à jour');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Article supprimé');
    }
}

