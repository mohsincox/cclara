<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('id', "desc")->get();
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:255',
        ]);

        $book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
        ]);

        return response()->json($book, 201);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string',
        ]);

        $book->update($validated);

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['message' => 'Book deleted']);
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }
}
