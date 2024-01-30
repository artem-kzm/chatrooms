<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [SignupController::class, 'signup']);

Route::middleware('auth:web')->group(function () {
    Route::get('/rooms', [RoomController::class, 'getRooms']);
    Route::post('/rooms', [RoomController::class, 'createRoom']);
    Route::get('/rooms/{room}', [RoomController::class, 'getRoom']);
    Route::post('/rooms/{room}', [RoomController::class, 'updateRoom']);
    Route::delete('/rooms/{room}', [RoomController::class, 'deleteRoom']);
});
