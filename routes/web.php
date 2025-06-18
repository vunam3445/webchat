<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendshipController;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\RotatingFileHandler;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Broadcast;
// Authentication routes
Route::prefix('auth')->middleware(['web'])->group(function () {
    Route::get('', [AuthController::class, 'index'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');
});
Route::get('/check-login', function () {
    return Auth::check() ? 'Logged in' : 'Not logged in';
});

Route::middleware('auth')->group(function () {
    // Danh sách cuộc trò chuyện, tạo mới, xem chi tiết
    Route::resource('conversations', ConversationController::class)->only([
        'index',
        'store',

        'destroy'  // list, create, detail, delete
    ]);
    // Tìm kiếm người dùng qua số điện thoại (search user by phone)
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');


    // Gửi lời mời kết bạn
    Route::post('/friends/request', [FriendshipController::class, 'sendRequest']);

    // Chấp nhận lời mời kết bạn
    Route::put('/friends/accept', [FriendshipController::class, 'acceptRequest']);

    // Từ chối lời mời kết bạn
    Route::delete('/friends/reject', [FriendshipController::class, 'rejectRequest']);

    // Lấy danh sách bạn bè
    Route::get('/friends', [FriendshipController::class, 'getFriends']);

    // Lấy danh sách lời mời kết bạn đang chờ
    Route::get('/friends/pending', [FriendshipController::class, 'getPendingRequests']);


    Route::post('/conversations/group', [ConversationController::class, 'createGroup'])->name('conversations.createGroup');
    Route::get('/conversations/group/{id}', [ConversationController::class, 'getGroupConversation'])->name('conversations.getGroupConversation');

    Route::get('/conversations/{id}/messages', [MessageController::class, 'getMessages']);
    Route::post('/conversations/{id}/messages', [MessageController::class, 'send']);
    Route::get('/conversations/{id}', [ConversationController::class, 'getConversation'])->name('conversations.getConversation');

    Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
        return true; // hoặc kiểm tra quyền truy cập nếu cần
    });
});
