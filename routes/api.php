<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendshipController;
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/friends/request', [FriendshipController::class, 'sendRequest']);
