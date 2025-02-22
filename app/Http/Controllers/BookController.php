<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Get the information of a book
    public function getBook($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book
        ], 201);
    }

    public function getallbooks()
    {
        $books = book::paginate(5);
        return new BookCollection($books);
    }

    public function store(Request $request)
    {
        $book = new Book();
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:2048', // Máx 2MB
            'image' => 'required|file|mimes:jpg,png,jpeg|max:2048', // Máx 2MB
        ]);
        $filePath = $request->file('file')->store('books', 'public');
        $imgPath = $request->file('image')->store('images', 'public');
        $book->title = $request->title;
        $book->synopsis = $request->synopsis;
        $book->category = $request->category;
        $book->file = $filePath;
        $book->image = $imgPath;

        $book->save();
        return response()->json([
            'message' => 'Book stored successfully',
            'book' => $book
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'image' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
        ]);

        $book->title = $request->title;
        $book->synopsis = $request->synopsis;
        $book->category = $request->category;

        // Update the book file if new file 
        if ($request->hasFile('file')) {
            if ($book->file && Storage::disk('public')->exists($book->file)) {
                Storage::disk('public')->delete($book->file);
            }
            $filePath = $request->file('file')->store('books', 'public');
            $book->file = $filePath;
        }

        // Update the book image if new file 
        if ($request->hasFile('image')) {
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $imgPath = $request->file('image')->store('images', 'public');
            $book->image = $imgPath;
        }

        $book->save();

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ], 200);
    }



    public function download($id)
    {
        $book = Book::findOrFail($id);

        return response()->download(storage_path("app/public/{$book->file}"));
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->file && Storage::disk('public')->exists($book->file)) {
            Storage::disk('public')->delete($book->file);
        }
        $book->delete();

        return response()->json([
            'message' => 'book eliminado correctamente'
        ], 200);
    }
}
