<?php


use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


// Route public cho books (categoryId optional)
Route::get('/', [HomeController::class, 'index']);
Route::get('/books/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');

// Route nhóm admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
             return view('admin.dashboard');
         });
    Route::prefix('books')->name('books.')->group(function(){
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/show/{id}/{slug}', [BookController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{slug}', [BookController::class, 'edit'])->name('edit');
        Route::put('/update/{id}/{slug}', [BookController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BookController::class, 'destroy'])->name('destroy');
        
        // Trash routes
        Route::get('/trash', [BookController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [BookController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [BookController::class, 'forceDelete'])->name('force-delete');
    })->name('dashboard');
  
    // Route admin/categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
    });

    // Route admin/users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
    });
});
