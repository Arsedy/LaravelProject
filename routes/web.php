<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/');
Route::get('/store', [HomeController::class, 'store'])->name('store');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/blank', [HomeController::class, 'blank'])->name('blank');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin.home');
