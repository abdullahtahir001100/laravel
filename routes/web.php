<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserSettingsController;
use App\Models\User;

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
    })->name('facebook');

    // User Directory
    Route::get('/users', function () {
        return view('user');
    })->name('users.index');
    Route::view('/Posts', 'posts')->name('posts.index');
   Route::view('/reels', 'reels')->name('reels.index');
   Route::view('/notifications', 'notifications')->name('notifications.index');
   Route::view('/create', 'create')->name('create.index');
   Route::get('/profile', function () {
       return redirect()->route('user.profile', auth()->id());
   })->name('user-profile.index');
   Route::view('/friends', 'friends')->name('friends.index');
    Route::view('/discover', 'live')->name('live.index');
    Route::view('/live', 'live')->name('live.page');
   Route::view('/settings', 'settings')->name('settings.index');
   Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/thread', [MessageController::class, 'thread'])->name('messages.thread');
   Route::post('/messages/send', [MessageController::class, 'store'])->name('messages.store');
   Route::post('/messages/read', [MessageController::class, 'markRead'])->name('messages.read');
   Route::post('/send', [MessageController::class, 'store']);
   Route::get('/user/{user}', function (User $user) {
        return view('user-profile', [
            'profileUser' => $user,
            'isOwnProfile' => auth()->id() === $user->id,
        ]);
   })->name('user.profile');

    // Settings + Profile backend
    Route::get('/api/settings/read', [UserSettingsController::class, 'read'])->name('settings.read');
    Route::put('/api/settings/update', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('/api/profile/media', [UserSettingsController::class, 'updateMedia'])->name('profile.media.update');

    Route::get('/api/content-items', [ContentItemController::class, 'index'])->name('content-items.index');
    Route::post('/api/content-items', [ContentItemController::class, 'store'])->name('content-items.store');
    Route::patch('/api/content-items/{contentItem}/visibility', [ContentItemController::class, 'updateVisibility'])->name('content-items.visibility');
    Route::delete('/api/content-items/{contentItem}', [ContentItemController::class, 'destroy'])->name('content-items.destroy');

    Route::get('/api/feed', [SocialController::class, 'feed'])->name('api.feed');
    Route::post('/api/content-items/{contentItem}/like', [SocialController::class, 'toggleLike'])->name('api.content-items.like');
   
    Route::get('/api/content-items/{contentItem}/comments', [SocialController::class, 'comments'])->name('api.content-items.comments.index');
    Route::post('/api/content-items/{contentItem}/comments', [SocialController::class, 'addComment'])->name('api.content-items.comments.store');
    Route::post('/api/content-items/{contentItem}/not-interested', [SocialController::class, 'notInterested'])->name('api.content-items.not-interested');

    Route::get('/api/users/suggestions', [SocialController::class, 'suggestedUsers'])->name('api.users.suggestions');
    Route::get('/api/users/search', [SocialController::class, 'userSearch'])->name('api.users.search');
    Route::get('/api/users/{user}/follow-status', [SocialController::class, 'followStatusForUser'])->name('api.users.follow-status');
    Route::post('/api/users/{user}/follow', [SocialController::class, 'follow'])->name('api.users.follow');
    Route::post('/api/follows/{follow}/accept', [SocialController::class, 'acceptFollow'])->name('api.follows.accept');
    Route::delete('/api/follows/{follow}/cancel', [SocialController::class, 'cancelFollow'])->name('api.follows.cancel');
    Route::delete('/api/follows/{follow}/reject', [SocialController::class, 'rejectFollow'])->name('api.follows.reject');
    Route::get('/api/friends', [SocialController::class, 'friends'])->name('api.friends');
    Route::get('/api/followers', [SocialController::class, 'followers'])->name('api.followers');
    Route::get('/api/following', [SocialController::class, 'following'])->name('api.following');
    Route::get('/api/notifications', [SocialController::class, 'notifications'])->name('api.notifications');

    // Logout Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/test', function () {
    return view('welcome');
});