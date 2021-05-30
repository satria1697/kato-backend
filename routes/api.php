<?php

use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Data\CartController;
use App\Http\Controllers\Api\Data\CategoriesController;
use App\Http\Controllers\Api\Data\VerificationController;
use App\Http\Controllers\Api\Data\VerificationStatusController;
use App\Http\Controllers\Api\Data\CartStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/goods')->group(function() {

    Route::prefix('/cart')->group(function () {
        Route::middleware(['auth-jwt', 'checkuser'])->group(function() {
            Route::post('/checkout', [\App\Http\Controllers\Api\Data\CartController::class, 'checkout']);
            Route::post('/find', [\App\Http\Controllers\Api\Data\CartController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Data\CartController::class, 'remove']);
            Route::post('', [\App\Http\Controllers\Api\Data\CartController::class, 'store']);
        });

        Route::middleware(['auth-jwt', 'checkadmin'])->group(function() {
            Route::get('/', [CartController::class, 'index']);
        });
    });

    Route::prefix('/category')->group(function() {
        Route::middleware(['auth-jwt', 'checkadmin'])->group(function () {
            Route::get('{id}', [CategoriesController::class, 'show']);
            Route::post('{id}', [CategoriesController::class, 'update']);
            Route::post('', [CategoriesController::class, 'store']);
            Route::delete('{id}', [CategoriesController::class, 'destroy']);
        });
        Route::get('', [CategoriesController::class, 'index']);
    });

    Route::middleware(['auth-jwt', 'checkadmin'])->group(function () {
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
    Route::middleware(['auth-jwt', 'checkadmin'])->group(function() {
        Route::post('{slug}', [\App\Http\Controllers\Api\Data\ArticleController::class, 'update']);
        Route::post('', [\App\Http\Controllers\Api\Data\ArticleController::class, 'store']);
        Route::delete('{id}', [\App\Http\Controllers\Api\Data\ArticleController::class, 'remove']);
    });
});

Route::prefix('/profile')->group(function () {
    Route::get('{id}', [ProfileController::class, 'show']);
    Route::get('', [ProfileController::class, 'index']);
    Route::middleware(['checkuser'])->group(function() {
        Route::post('{id}', [ProfileController::class, 'update']);
    });

});

Route::prefix('/id.verification')->group(function() {
    Route::prefix('/status')->group(function() {
        Route::get('', [VerificationStatusController::class, 'index']);
    });
    Route::middleware(['auth-jwt', 'checkadmin'])->group(function () {
        Route::post('/updateStatus/{id}', [VerificationController::class, 'updateStatus']);
        Route::post('{id}', [VerificationController::class, 'update']);
    });
});

Route::prefix('cart')->group(function () {
    Route::middleware(['auth-jwt', 'checkadmin'])->group(function() {
        Route::post('/update', [CartStatusController::class, 'updateCart']);
        Route::post('/', [CartController::class, 'getOne']);
        Route::get('/status', [CartStatusController::class, 'index']);
    });
});
