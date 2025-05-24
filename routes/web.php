<?php


use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wishlists\WishlistController;

// Route public cho books (categoryId optional)
Route::post('/wishlist/add', [WishlistController::class, 'add']);
Route::get('/', [HomeController::class, 'index']);
Route::get('/', [HomeController::class, 'index']);
// Hiển thị danh sách và danh mục
Route::get('/books/{slug?}', [BookController::class, 'index'])->name('books.index');
// Hiển thị chi tiết sách
Route::get('/book/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');

// Route nhóm admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
             return view('admin.dashboard');
         });
    Route::prefix('books')->name('books.')->group(function(){
        Route::get('/', [AdminBookController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookController::class, 'create'])->name('create');
        Route::post('/store', [AdminBookController::class, 'store'])->name('store');
        Route::get('/show/{id}/{slug}', [AdminBookController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{slug}', [AdminBookController::class, 'edit'])->name('edit');
        Route::put('/update/{id}/{slug}', [AdminBookController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminBookController::class, 'destroy'])->name('destroy');
        
        // Trash routes
        Route::get('/trash', [AdminBookController::class, 'trash'])->name('trash');
        Route::post('/restore/{id}', [AdminBookController::class, 'restore'])->name('restore');
        Route::delete('/force-delete/{id}', [AdminBookController::class, 'forceDelete'])->name('force-delete');
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

// Route cho wishlist
Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist.index');
Route::post('/wishlist/delete', [WishlistController::class, 'delete'])->name('wishlist.delete');
Route::post('/wishlist/delete-all', [WishlistController::class, 'deleteAll'])->name('wishlist.delete-all');
