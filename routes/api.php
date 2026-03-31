<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CategorieController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me',       [AuthController::class, 'me']);

    Route::get('/products',       [ProduitController::class, 'index']);
    Route::get('/products/{slug}', [ProduitController::class, 'show']);

    Route::get('/categories',        [CategorieController::class, 'index']);
    Route::get('/categories/{slug}', [CategorieController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/products',          [ProduitController::class, 'store']);
        Route::put('/products/{slug}',    [ProduitController::class, 'update']);
        Route::delete('/products/{slug}', [ProduitController::class, 'destroy']);

        Route::post('/categories',          [CategorieController::class, 'store']);
        Route::put('/categories/{slug}',    [CategorieController::class, 'update']);
        Route::delete('/categories/{slug}', [CategorieController::class, 'destroy']);

    });
});
