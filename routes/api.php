<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/books', [BookController::class, 'store'])->name('book.store');
Route::post('/books/{book}', [BookController::class, 'update'])->name('book.update');
Route::get('/books/{book}/download', [BookController::class, 'download'])->name('book.download');
Route::get('/books/{book}', [BookController::class, 'getBook'])->name('book.getBook');
Route::get('/books', [BookController::class, 'getAllBooks'])->name('book.getAllBooks');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('book.destroy');
