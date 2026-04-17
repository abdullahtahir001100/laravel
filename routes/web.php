<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SimpleController;
use App\Http\Controllers\UserController;

Route::view('/', 'add_product');

Route::post('/save-product', [ProductController::class, 'store']);

Route::post('/simplecontroller_make', [SimpleController::class, 'create']);

Route::get('/simple', [SimpleController::class, 'index']);

Route::get('/simplecontroller_edit/{id}', [SimpleController::class, 'edit']);

Route::post('/simplecontroller_update/{id}', [SimpleController::class, 'update']);

Route::post('/simplecontroller_destroy', [SimpleController::class, 'destroy']);






Route::get('/user1', [UserController::class, 'show']);
Route::get('/user', [UserController::class, 'index']);
