<?php

use Illuminate\Support\Facades\Route;




use App\Http\Controllers\ProductController;

Route::view('/', 'add_product');
Route::view('/view', 'view_product');
Route::view('/edit', 'edit_product');
Route::get('/get-product', [ProductController::class, 'show']);
Route::post('/save-product', [ProductController::class, 'store']);
Route::get('/view-products', [ProductController::class, 'get']);


