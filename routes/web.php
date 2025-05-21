<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/books', [BookController::class, 'index'])->name('books.index');
// Route có tham số categoryId là optional (có hoặc không)
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');
?>