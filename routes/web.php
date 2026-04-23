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






Route::get('/user', [UserController::class, 'show']);
Route::get('/api/user/', [UserController::class, 'index']);
Route::post('/api/user/', [UserController::class, 'store']);
Route::view('/user/create', 'create_user');
Route::get('/user/edit/{id}', [UserController::class, 'edit']);
Route::post('/user/update/{id}', [UserController::class, 'update']);
Route::post('/user/destroy', [UserController::class, 'destroy']);
Route::get('/user/destroy/{id}', [UserController::class, 'destroy']);
Route::post('/user/reset', [UserController::class, 'reset']);
Route::post('/user/login', [UserController::class, 'login']);

















Route::view('/facebook','facebook_login');
Route::view('/facebook_page','mainpage');
