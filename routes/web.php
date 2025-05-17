<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;



Route::get('/',[HomeController::class,'index']);

// Route::get('/', function () {
//     return view('welcome');
// });
Route::prefix('admin')->name('admin.')->group(function(){
    Route::get('/', function () {
             return view('admin.dashboard');
         });
 });
