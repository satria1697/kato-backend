<?php

use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Data\CategoriesController;
use App\Http\Controllers\Api\Data\VerificationController;
use App\Http\Controllers\Api\Data\VerificationStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/goods')->group(function() {

    Route::prefix('/cart')->group(function () {
        Route::middleware(['auth:api'])->group(function() {
            Route::post('/checkout', [\App\Http\Controllers\Api\Data\CartController::class, 'checkout']);
            Route::post('/find', [\App\Http\Controllers\Api\Data\CartController::class, 'show']);
            Route::post('', [\App\Http\Controllers\Api\Data\CartController::class, 'store']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Data\CartController::class, 'remove']);
        });
    });

    Route::prefix('/category')->group(function() {
        Route::get('', [CategoriesController::class, 'index']);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::post('{id}', [\App\Http\Controllers\Api\Data\GoodsController::class, 'update']);
        Route::post('', [\App\Http\Controllers\Api\Data\GoodsController::class, 'store']);
        Route::delete('{id}', [\App\Http\Controllers\Api\Data\GoodsController::class, 'delete']);
    });
    Route::get('{id}', [\App\Http\Controllers\Api\Data\GoodsController::class, 'view']);
    Route::get('', [\App\Http\Controllers\Api\Data\GoodsController::class, 'index']);
});

Route::prefix('/article')->group(function () {
    Route::get('{slug}', [\App\Http\Controllers\Api\Data\ArticleController::class, 'show']);
    Route::get('', [\App\Http\Controllers\Api\Data\ArticleController::class, 'index']);
    Route::middleware(['auth:api'])->group(function() {
        Route::post('{slug}', [\App\Http\Controllers\Api\Data\ArticleController::class, 'update']);
        Route::post('', [\App\Http\Controllers\Api\Data\ArticleController::class, 'store']);
        Route::delete('{id}', [\App\Http\Controllers\Api\Data\ArticleController::class, 'remove']);
    });
});

Route::prefix('/profile')->group(function () {
    Route::get('{id}', [ProfileController::class, 'show']);
    Route::get('', [ProfileController::class, 'index']);
    Route::post('{id}', [ProfileController::class, 'update']);
});

Route::prefix('/id.verification')->group(function() {
    Route::prefix('/status')->group(function() {
        Route::get('', [VerificationStatusController::class, 'index']);
    });
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/updateStatus/{id}', [VerificationController::class, 'updateStatus']);
        Route::post('{id}', [VerificationController::class, 'update']);
    });
});
