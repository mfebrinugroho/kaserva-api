<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class)->only(['index', 'show']);
Route::apiResource('roles', RoleController::class);
Route::apiResource('stores', StoreController::class);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);

    // Get User
    Route::get('/user', [AuthController::class, 'me']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Super Admin
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/super-admin-test', function () {
            return response()->json([
                'message' => 'Super Admin Access'
            ]);
        });

        Route::apiResource('users', UserController::class);
    });

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-test', function () {
            return response()->json([
                'message' => 'Admin Access'
            ]);
        });
    });

    // Customer
    Route::middleware('role:customer')->group(function () {
        Route::get('/customer-test', function () {
            return response()->json([
                'message' => 'Customer Access'
            ]);
        });
    });

    // Cashier
    Route::middleware('role:cashier')->group(function () {
        Route::get('/cashier-test', function () {
            return response()->json([
                'message' => 'Cashier Access'
            ]);
        });
    });

    // Kitchen
    Route::middleware('role:kitchen')->group(function () {
        Route::get('/kitchen-test', function () {
            return response()->json([
                'message' => 'Kitchen Access'
            ]);
        });
    });
});
