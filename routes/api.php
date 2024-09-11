<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('login', [LoginController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    require __DIR__ . '/api_v1.php';
    require __DIR__ . '/api_v2.php';
});


