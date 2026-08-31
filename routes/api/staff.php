<?php

use App\Http\Controllers\Api\V1\Staff\MenuCategoryController;
use App\Http\Controllers\Api\V1\Staff\MenuController;
use App\Http\Controllers\Api\V1\Staff\UserController;
use App\Http\Controllers\Api\V1\Staff\RoleController;
use App\Http\Controllers\Api\V1\Staff\StoreController;
use App\Http\Controllers\Api\V1\Staff\StoreOperatingHourController;
use App\Http\Controllers\Api\V1\Staff\StoreOwnerController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

  /*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/

  Route::prefix('staff')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
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
        Route::post('/stores/store-owner', [StoreOwnerController::class, 'addOwner']);
        Route::prefix('stores-owners')->group(function () {
          Route::get('/available-users', [StoreOwnerController::class, 'availableUsers']);
          Route::get('/available-stores', [StoreOwnerController::class, 'availableStores']);
        });

        Route::post('/user/active-store', [UserController::class, 'activeStore']);

        Route::apiResource('menus', MenuController::class);
        Route::patch('/menus/{menu}/status-available', [MenuController::class, 'updateStatus']);

        Route::get('/menu-categories/options', [MenuCategoryController::class, 'options']);
        Route::apiResource('menu-categories', MenuCategoryController::class);
        Route::patch('/menu-categories/{menu_category}/status', [MenuCategoryController::class, 'updateStatus']);
      });
    });
  });
});
