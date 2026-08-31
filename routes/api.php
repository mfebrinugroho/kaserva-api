<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
  return response()->json([
    'success' => true,
    'message' => 'Kaserva API is running',
  ]);
});

require __DIR__ . '/api/customer.php';
require __DIR__ . '/api/staff.php';
