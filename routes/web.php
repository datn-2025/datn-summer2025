<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// Route public cho books (categoryId optional)
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');

// Route nhóm admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Route admin/books
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookController::class, 'create'])->name('create');
        Route::get('/show/{id}/{slug}', [AdminBookController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{slug}', [AdminBookController::class, 'edit'])->name('edit');
        Route::put('/update/{id}/{slug}', [AdminBookController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminBookController::class, 'destroy'])->name('destroy');
    });

    // Route admin/categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        // Route::delete('/soft-delete/{id}', [CategoryController::class, 'softDelete'])->name('soft-delete');
        // Route::delete('/force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('force-delete');
    });

    // Route admin/users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
    });
});
