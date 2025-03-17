<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::post('/store', [ProductController::class, 'store'])->name('products.store');
Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::get('/data', [ProductController::class, 'fetchData'])->name('products.data');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
