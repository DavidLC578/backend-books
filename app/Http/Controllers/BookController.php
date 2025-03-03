<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Get the information of a book
    public function getBook($id)
    {
        $book = Book::findOrFail($id);

        $book->image_url = asset('storage/' . $book->image);

        return response()->json([
            'book' => $book
        ], 201);
    }

    public function getAllBooks()
    {
        $books = Book::with('user')->get(); // Cargar relación con usuario en una sola consulta

        // Modificar la colección para agregar la URL de la imagen
        $books->transform(function ($book) {
            $book->image_url = asset('storage/' . $book->image);
            return $book;
        });

        return response()->json(['data' => $books], 200);
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
        $book->user_id = Auth::user()->id;

        $book->save();
        return response()->json([
            'message' => 'Book stored successfully',
            'book' => $book
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($book->user_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

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
        if ($book->user_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

        if ($book->file && Storage::disk('public')->exists($book->file)) {
            Storage::disk('public')->delete($book->file);
        }
        $book->delete();

        return response()->json([
            'message' => 'book eliminado correctamente'
        ], 200);
    }
}
