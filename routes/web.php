<?php


use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


// Route public cho books (categoryId optional)
Route::get('/', [HomeController::class, 'index']);
Route::get('/books/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');

// Test route for QR code generation
Route::get('/test-qr-code/{id}', function($id) {
    $order = \App\Models\Order::findOrFail($id);
    $controller = new \App\Http\Controllers\Admin\OrderController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('generateQrCode');
    $method->setAccessible(true);
    $method->invoke($controller, $order);
    
    return redirect()->route('admin.orders.show', $order->id)->with('success', 'QR Code generated successfully!');
});

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
    });
  
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
    
    // Route admin/attributes
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::get('/create', [AttributeController::class, 'create'])->name('create');
        Route::post('/store', [AttributeController::class, 'store'])->name('store');
        Route::get('/show/{id}', [AttributeController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [AttributeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AttributeController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AttributeController::class, 'destroy'])->name('destroy');
    });
    
    // Route admin/orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [OrderController::class, 'update'])->name('update');
    });
});
