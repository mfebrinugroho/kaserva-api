<?php

use App\Http\Controllers\Api\Staff\MenuCategoryController;
use App\Http\Controllers\Api\Staff\MenuController;
use App\Http\Controllers\Api\Staff\UserController;
use App\Http\Controllers\Api\Staff\RoleController;
use App\Http\Controllers\Api\Staff\StoreController;
use App\Http\Controllers\Api\Staff\StoreOperatingHourController;
use App\Http\Controllers\Api\Staff\StoreOwnerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/

Route::prefix('staff')->group(function () {
  Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
  });

  Route::middleware('jwt')->group(function () {
    Route::prefix('auth')->group(function () {
      Route::get('/me', [AuthController::class, 'me']);
      Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Users
    Route::prefix('users')->group(function () {
      Route::get('/owners', [UserController::class, 'owners']);
      Route::post('/active-store', [UserController::class, 'activeStore']);
    });

    Route::apiResource('users', UserController::class)->middleware('role:super-admin');

    // Roles
    Route::apiResource('roles', RoleController::class);

    // Stores
    Route::prefix('stores')->group(function () {
      Route::get('/options', [StoreController::class, 'options']);
      Route::get('/without-owner', [StoreController::class, 'storesWithoutOwner']);
      Route::post('/add-owner', [StoreController::class, 'addOwner']);
    });

    Route::apiResource('stores', StoreController::class);

    Route::prefix('stores/{store}')->group(function () {
      Route::patch('/status', [StoreController::class, 'updateStatus']);
      Route::patch('/status-operational', [StoreController::class, 'updateStatusOperational']);
      Route::patch('/status-order', [StoreController::class, 'updateStatusOrder']);

      Route::get(
        '/operating-hours',
        [StoreOperatingHourController::class, 'index']
      );

      Route::put(
        '/operating-hours',
        [StoreOperatingHourController::class, 'update']
      );
    });

    // Menu Categories
    Route::get('/menu-categories/options', [MenuCategoryController::class, 'options']);
    Route::apiResource('menu-categories', MenuCategoryController::class);
    Route::patch('/menu-categories/{menu_category}/status', [MenuCategoryController::class, 'updateStatus']);

    // Menus
    Route::apiResource('menus', MenuController::class);
    Route::patch('/menus/{menu}/status-available', [MenuController::class, 'updateStatus']);
  });
});
