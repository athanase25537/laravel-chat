<?php

use App\Events\OnlineOffline;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::get('/friends', [FriendController::class, 'getAllFriends'])
    ->name('friends');

Route::get('/friends/{id}', [FriendController::class, 'removeFriend'])
->name('delete');

Route::get('/add/{id}', [FriendController::class, 'addFriend'])
->name('add');

Route::get('/chats/{id}', [MessageController::class, 'getMessages'])
->name('chats');

Route::post('/new/{id}', [MessageController::class, 'getNewMessage'])
->name('new-chat');

Route::post('/posts/{id}', [MessageController::class, 'post'])
    ->name('posts');

Route::get('/friend/status/{id}', [MessageController::class, 'getStatus'])
    ->name('friend.status');

Route::get('/all/status', [MessageController::class, 'getAllStatus'])
->name('all.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
