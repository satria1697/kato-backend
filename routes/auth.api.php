<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LevelController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/verification', [AuthController::class, 'verification']);

Route::post('/level', [LevelController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);
});
