<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
    public function index()
    {
        return Content::where('user_id', auth()->id())->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $content = auth()->user()->contents()->create($validated);

        return response()->json($content, 201);
    }

    public function update(Request $request, Content $content)
    {
        abort_if($content->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $content->update($validated);

        return response()->json($content);
    }

    public function destroy(Content $content)
    {
        abort_if($content->user_id !== auth()->id(), 403);

        $content->delete();

        return response()->json(['message' => 'Content deleted']);
    }

    public function show(Content $content)
    {
        // Ensure only owner can view
        if ($content->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($content);
    }
}
