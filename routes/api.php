<?php

use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreOwnerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::apiResource('posts', PostController::class)->only(['index', 'show']);

Route::get('/example', function () {
    return response()->json([
        'message' => 'Example Test'
    ]);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('posts', PostController::class)->except(['index', 'show']);

    // Get User Login
    Route::get('/user', [AuthController::class, 'me']);
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    // Role
    Route::apiResource('roles', RoleController::class);

    // Super Admin
    Route::middleware('role:super-admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Owner
    Route::middleware('role:owner,super-admin')->group(function () {
        Route::get('/stores/options', [StoreController::class, 'options']);
        Route::apiResource('stores', StoreController::class);
        Route::patch('/stores/{store}/status', [StoreController::class, 'updateStatus']);
        Route::patch('/stores/{store}/status-operational', [StoreController::class, 'updateStatusOperational']);
        Route::patch('/stores/{store}/status-order', [StoreController::class, 'updateStatusOrder']);
        Route::post('/stores/store-owner', [StoreOwnerController::class, 'addOwner']);
        Route::get('/stores-owners/available-users', [StoreOwnerController::class, 'availableUsers']);
        Route::get('/stores-owners/available-stores', [StoreOwnerController::class, 'availableStores']);


        Route::post('/user/active-store', [UserController::class, 'activeStore']);

        Route::apiResource('menus', MenuController::class);
        Route::patch('/menus/{menu}/status-available', [MenuController::class, 'updateStatus']);

        Route::get('/menu-categories/options', [MenuCategoryController::class, 'options']);
        Route::apiResource('menu-categories', MenuCategoryController::class);
        Route::patch('/menu-categories/{menu_category}/status', [MenuCategoryController::class, 'updateStatus']);
    });

    // Admin
    // Route::middleware('role:admin')->group(function () {
    //     Route::get('/admin-test', function () {
    //         return response()->json([
    //             'message' => 'Admin Access'
    //         ]);
    //     });
    // });

    // Customer
    // Route::middleware('role:customer')->group(function () {
    //     Route::get('/customer-test', function () {
    //         return response()->json([
    //             'message' => 'Customer Access'
    //         ]);
    //     });
    // });

    // Cashier
    // Route::middleware('role:cashier')->group(function () {
    //     Route::get('/cashier-test', function () {
    //         return response()->json([
    //             'message' => 'Cashier Access'
    //         ]);
    //     });
    // });

    // Kitchen
    // Route::middleware('role:kitchen')->group(function () {
    //     Route::get('/kitchen-test', function () {
    //         return response()->json([
    //             'message' => 'Kitchen Access'
    //         ]);
    //     });
    // });
});
