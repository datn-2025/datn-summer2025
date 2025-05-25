<?php


use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


// Route public cho books (categoryId optional)
Route::get('/', [HomeController::class, 'index']);
Route::get('/books/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');

// Route nhóm admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard route
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard.index');

    Route::prefix('books')->name('books.')->group(function () {
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

    // Voucher routes
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        // Route để lấy danh sách đối tượng theo điều kiện
        Route::get('/get-condition-options', [VoucherController::class, 'getConditionOptions'])
            ->name('getConditionOptions');
        Route::get('/search', [VoucherController::class, 'search'])->name('search');

        // Trash routes - Đặt trước các route khác
        Route::get('/trash', [VoucherController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [VoucherController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [VoucherController::class, 'forceDelete'])->name('force-delete');

        // Các route CRUD thông thường
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');

        Route::get('/export', [VoucherController::class, 'export'])->name('export');
    });
});
