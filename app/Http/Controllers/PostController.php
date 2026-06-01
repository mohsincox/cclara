<?php

/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index() {
        return Post::all();
    }

    public function store(Request $request) {
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    public function show($id) {
        return Post::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
    }

    public function destroy($id) {
        Post::destroy($id);
        return response()->json(null, 204);
    }
}
*/


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::where('user_id', auth()->id())->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = auth()->user()->posts()->create($validated);

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function show(Post $post)
    {
        // Ensure only owner can view
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }
}