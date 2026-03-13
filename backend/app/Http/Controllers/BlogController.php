<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of public blog posts.
     */
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
            ->with('author:id,name')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return response()->json($posts);
    }

    /**
     * Display a listing of all blog posts for admin.
     */
    public function adminIndex()
    {
        $posts = BlogPost::with('author:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($posts);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $post = BlogPost::create(array_merge($validated, [
            'author_id' => auth()->id(),
            'published_at' => ($request->is_published && !$request->published_at) ? now() : $request->published_at,
        ]));

        return response()->json($post, 21);
    }

    /**
     * Display the specified post.
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->with('author:id,name')
            ->firstOrFail();

        if (!$post->is_published && (!auth()->check() || !auth()->user()->hasRole('admin'))) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($post);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (isset($validated['title']) && $validated['title'] !== $blogPost->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if (isset($validated['is_published']) && $validated['is_published'] && !$blogPost->is_published) {
            $validated['published_at'] = now();
        }

        $blogPost->update($validated);

        return response()->json($blogPost);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();
        return response()->json(null, 24);
    }
}