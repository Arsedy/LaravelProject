<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/');
Route::get('/store', [HomeController::class, 'store'])->name('store');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/blank', [HomeController::class, 'blank'])->name('blank');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});
