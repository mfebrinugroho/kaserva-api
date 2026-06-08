<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Post',
            'data' => PostResource::collection($posts),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post = $request->user()->posts()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => new PostResource($post),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        return response()->json([
            'success' => true,
            'message' => 'Detail Post',
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }
}
