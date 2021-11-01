<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/',[\App\Http\Controllers\ProductController::class,'index'])->middleware('auth');


Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
