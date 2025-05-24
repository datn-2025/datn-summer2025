<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        
        return view('admin.dashboard');
    })->name('dashboard');;
Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');

});
