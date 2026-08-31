<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  /*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

  Route::prefix('customer')->group(function () {});
});
