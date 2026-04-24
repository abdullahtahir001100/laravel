<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for users NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Show Login/Register/Forgot Page
    Route::get('/', [AuthController::class, 'showAuth'])->name('login');
    
    // Process Login & Register
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Only for users WHO ARE logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Main Dashboard/Feed
    Route::get('/facebook', function () {
        return view('mainpage');
    })->name('dashboard');

    // User Directory
    Route::get('/users', function () {
        return view('user');
    })->name('users.index');
    Route::view('/Posts', 'posts')->name('posts.index');
   Route::view('/reels', 'reels')->name('reels.index');
   Route::view('/notifications', 'notifications')->name('notifications.index');
   Route::view('/create', 'create')->name('create.index');
   Route::view('/profile', 'profile')->name('profile.index');
   Route::view('/friends', 'friends')->name('friends.index');
   Route::view('/discover', 'live')->name('live.index');
   Route::view('/settings', 'settings')->name('settings.index');
   Route::view('/messages', 'messages')->name('messages.index');
   Route::view('/user/{id}', 'user-profile')->name('user.profile');

    // Logout Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});