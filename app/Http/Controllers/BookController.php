<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function getBook($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book
        ], 201);
    }
    public function store(Request $request)
    {
        $book = new Book();
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:2048', // Máx 2MB
        ]);
        $filePath = $request->file('file')->store('books', 'public');
        $book->title = $request->title;
        $book->synopsis = $request->synopsis;
        $book->category = $request->category;
        $book->file = $filePath;

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
            'file' => 'nullable|file|mimes:pdf|max:2048' // Optional: if a new PDF is uploaded
        ]);

        // Update the title
        $book->title = $request->title;
        $book->synopsis = $request->synopsis;
        $book->category = $request->category;

        // If a new file is uploaded, delete the old one and save the new one
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($book->file && Storage::disk('public')->exists($book->file)) {
                Storage::disk('public')->delete($book->file);
            }

            // Save the new file
            $filePath = $request->file('file')->store('books', 'public');
            $book->file = $filePath;
        }
        // Save changes
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
