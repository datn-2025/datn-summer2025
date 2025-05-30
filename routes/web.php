<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
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
use App\Http\Controllers\Wishlists\WishlistController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Article\NewsController;
use App\Http\Controllers\Admin\NewsArticleController;
use App\Http\Controllers\Admin\PaymentMethodController;

// danh sach yeu thich 
Route::get('/wishlist', [WishlistController::class, 'getWishlist'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/delete', [WishlistController::class, 'delete'])->name('wishlist.delete');
Route::post('/wishlist/delete-all', [WishlistController::class, 'deleteAll'])->name('wishlist.delete-all');
Route::post('/wishlist/add-to-cart', [WishlistController::class, 'addToCartFromWishlist'])->name('wishlist.addToCart');
// Hiển thị danh sách và danh mục

// Route public cho books (categoryId optional)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/books/{slug?}', [BookController::class, 'index'])->name('books.index');
Route::get('/book/{slug}', [HomeController::class, 'show'])->name('books.show');
Route::get('/books/{categoryId?}', [BookController::class, 'index'])->name('books.index');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');


// Test route for QR code generation
Route::get('/test-qr-code/{id}', function ($id) {
    $order = \App\Models\Order::findOrFail($id);
    $controller = new \App\Http\Controllers\Admin\OrderController();
    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('generateQrCode');
    $method->setAccessible(true);
    $method->invoke($controller, $order);
    return redirect()->route('admin.orders.show', $order->id)->with('success', 'QR Code generated successfully!');
});    // Route nhóm admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        Toastr::info('Chào mừng bạn đến với trang quản trị!', 'Thông báo');
        return view('admin.dashboard');
    });


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
    });
    // Admin Payment Methods
    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
        Route::get('/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
        // Thêm các route mới
        Route::get('/trash', [PaymentMethodController::class, 'trash'])->name('trash');
        Route::put('/{paymentMethod}/restore', [PaymentMethodController::class, 'restore'])->name('restore');
        Route::delete('/{paymentMethod}/force-delete', [PaymentMethodController::class, 'forceDelete'])->name('force-delete');
    });
  
    // Route admin/categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        // Route admin/brand
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [CategoryController::class, 'brand'])->name('brand');
            Route::get('/create', [CategoryController::class, 'BrandCreate'])->name('create');
            Route::post('/', [CategoryController::class, 'BrandStore'])->name('store');
            Route::get('/trash', [CategoryController::class, 'BrandTrash'])->name('trash');
            Route::delete('/{author}', [CategoryController::class, 'BrandDestroy'])->name('destroy');
            Route::put('/{id}/restore', [CategoryController::class, 'BrandRestore'])->name('restore');
            Route::delete('/{id}/force', [CategoryController::class, 'BrandForceDelete'])->name('force-delete');
            Route::get('/{id}/edit', [CategoryController::class, 'BrandEdit'])->name('edit');
            Route::put('/{id}', [CategoryController::class, 'BrandUpdate'])->name('update');
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
    // Route admin/contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('index');
        Route::get('/show/{id}', [AdminContactController::class, 'show'])->name('show');
        Route::put('/update/{id}', [AdminContactController::class, 'update'])->name('update'); // Cập nhật trạng thái
        Route::delete('/delete/{id}', [AdminContactController::class, 'destroy'])->name('destroy'); // Xóa liên hệ
        Route::post('/reply/{contact}', [AdminContactController::class, 'sendReply'])->name('reply'); // Gửi phản hồi
    });
    // Route admin/news
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsArticleController::class, 'index'])->name('index');
        Route::get('/create', [NewsArticleController::class, 'create'])->name('create');
        Route::post('/', [NewsArticleController::class, 'store'])->name('store');
        Route::get('/{article}', [NewsArticleController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [NewsArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [NewsArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [NewsArticleController::class, 'destroy'])->name('destroy');
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
    // Route::get('/', [LoginController::class, 'index'])->name('index');
    // Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'handleRegister'])->name('register.submit');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    // Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\Login\LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Login\LoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Login\LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Login\LoginController::class, 'handleResetPassword'])->name('password.update');

    // Activation routes
    Route::get('/activate/{userId}', [ActivationController::class, 'activate'])->name('activate');

    // profile
    Route::get('/showUser', [LoginController::class, 'showUser'])->name('showUser');
    Route::put('/profile/update', [LoginController::class, 'updateProfile'])->name('profile.update');
});




// Login và tài khoản
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    // Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Trang tài khoản
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [LoginController::class, 'index'])->name('index');
    });
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', function () {
            Toastr::info('Chào mừng bạn đến với trang quản trị!', 'Thông báo');
            return view('admin.dashboard');
        })->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

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
            Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [CategoryController::class, 'forceDelete'])->name('force-delete');

            // Route admin/brand
            Route::prefix('brands')->name('brands.')->group(function () {
                Route::get('/', [CategoryController::class, 'brand'])->name('brand');
                Route::get('/create', [CategoryController::class, 'BrandCreate'])->name('create');
                Route::post('/', [CategoryController::class, 'BrandStore'])->name('store');
                Route::get('/trash', [CategoryController::class, 'BrandTrash'])->name('trash');
                Route::delete('/{author}', [CategoryController::class, 'BrandDestroy'])->name('destroy');
                Route::put('/{id}/restore', [CategoryController::class, 'BrandRestore'])->name('restore');
                Route::delete('/{id}/force', [CategoryController::class, 'BrandForceDelete'])->name('force-delete');
                Route::get('/{id}/edit', [CategoryController::class, 'BrandEdit'])->name('edit');
                Route::put('/{id}', [CategoryController::class, 'BrandUpdate'])->name('update');
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
        // Admin Payment Methods
        Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
            Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
            Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
            Route::get('/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
            Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('update');
            Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('destroy');
            // Thêm các route mới
            Route::get('/trash', [PaymentMethodController::class, 'trash'])->name('trash');
            Route::put('/{paymentMethod}/restore', [PaymentMethodController::class, 'restore'])->name('restore');
            Route::delete('/{paymentMethod}/force-delete', [PaymentMethodController::class, 'forceDelete'])->name('force-delete');
        });

        Route::prefix('vouchers')->name('vouchers.')->group(function () {
            Route::get('/trash', [VoucherController::class, 'trash'])->name('trash');
            Route::post('{id}/restore', [VoucherController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [VoucherController::class, 'forceDelete'])->name('force-delete');
        });
        Route::resource('vouchers', VoucherController::class);

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
        });

        Route::prefix('attributes')->name('attributes.')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('index');
            Route::get('/create', [AttributeController::class, 'create'])->name('create');
            Route::post('/store', [AttributeController::class, 'store'])->name('store');
            Route::get('/show/{id}', [AttributeController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [AttributeController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [AttributeController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [AttributeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [OrderController::class, 'update'])->name('update');
        });
    });
});

