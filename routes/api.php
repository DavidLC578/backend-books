<?php

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/books', [BookController::class, 'store'])->name('book.store');
Route::post('/books/{book}', [BookController::class, 'update'])->name('book.update');
Route::get('/books/{book}/download', [BookController::class, 'download'])->name('book.download');
Route::get('/books/{book}', [BookController::class, 'getBook'])->name('book.getBook');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('book.destroy');
