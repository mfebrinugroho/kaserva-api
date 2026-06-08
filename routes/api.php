<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('stores', StoreController::class);
Route::apiResource('posts', PostController::class)->only(['index', 'show']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('posts', PostController::class)->except(['index', 'show']);
});
