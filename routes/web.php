<?php

use App\Http\Controllers\Admin\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->name('admin.')->group(function(){
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
    });
});
