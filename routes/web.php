<?php


use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
             return view('admin.dashboard');
         });
    Route::prefix('books')->name('books.')->group(function(){
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::get('/show/{id}/{slug}', [BookController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{slug}', [BookController::class, 'edit'])->name('edit');
        Route::put('/update/{id}/{slug}', [BookController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BookController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
    });
});
