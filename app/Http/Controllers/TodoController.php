<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // GET /api/todos
    public function index(): JsonResponse
    {
        $todos = Todo::orderBy('sort_order')->get();
        return response()->json($todos);
    }

    // POST /api/todos
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|min:3|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        $todo = Todo::create([
            'title'        => $validated['title'],
            'is_completed' => $validated['is_completed'] ?? false,
            'sort_order'   => Todo::max('sort_order') + 1,
        ]);

        return response()->json($todo, 201);
    }

    // PUT /api/todos/{id}
    public function update(Request $request, string $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'sometimes|string|min:3|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    // DELETE /api/todos/{id}
    public function destroy(string $id): JsonResponse
    {
        Todo::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    // PUT /api/todos/reorder
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ordered_ids'   => 'required|array',
            'ordered_ids.*' => 'required|string|exists:todos,id',
        ]);

        foreach ($validated['ordered_ids'] as $index => $id) {
            Todo::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Reordered']);
    }
}