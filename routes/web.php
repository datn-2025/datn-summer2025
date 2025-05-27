<?php


use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Login\ActivationController;
use App\Http\Controllers\HomeController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Article\NewsController;
use App\Http\Controllers\Cart\CartController;

// Route public cho books (categoryId optional)
Route::get('/', [HomeController::class, 'index'])->name('home');
// Hiển thị danh sách và danh mục
Route::get('/books/{slug?}', [BookController::class, 'index'])->name('books.index');
// Hiển thị chi tiết sách
Route::get('/book/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');



// cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.apply-voucher');
});

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
        Toastr::info('Chào mừng bạn đến với trang quản trị!', 'Thông báo');
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
        // Route admin/brand
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [CategoryController::class, 'brand'])->name('brand');
            // Route::get('/create', [AuthorController::class, 'create'])->name('create');
            // Route::post('/', [AuthorController::class, 'store'])->name('store');
            // Route::get('/trash', [AuthorController::class, 'trash'])->name('trash');
            // Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
            // Route::put('/{id}/restore', [AuthorController::class, 'restore'])->name('restore');
            // Route::delete('/{id}/force', [AuthorController::class, 'forceDelete'])->name('force-delete');
        });
        // Route admin/authors
        Route::prefix('authors')->name('authors.')->group(function () {
            Route::get('/', [AuthorController::class, 'index'])->name('index');
            Route::get('/create', [AuthorController::class, 'create'])->name('create');
            Route::post('/', [AuthorController::class, 'store'])->name('store');
            Route::get('/trash', [AuthorController::class, 'trash'])->name('trash');
            Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [AuthorController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [AuthorController::class, 'forceDelete'])->name('force-delete');
            Route::get('/{id}/edit', [AuthorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AuthorController::class, 'update'])->name('update');
        });
    });
    // Route admin/vouchers
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/trash', [VoucherController::class, 'trash'])->name('trash');
        Route::post('{id}/restore', [VoucherController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [VoucherController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('vouchers', VoucherController::class);

    

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
Route::prefix('account')->name('account.')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('index');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'handleRegister'])->name('register.submit');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Activation routes
    Route::get('/activate/{userId}', [ActivationController::class, 'activate'])->name('activate');

    // profile
    Route::get('/showUser', [LoginController::class, 'showUser'])->name('showUser');
    Route::put('/profile/update', [LoginController::class, 'updateProfile'])->name('profile.update');
});
