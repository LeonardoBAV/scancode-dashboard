<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('clients', [ClientController::class, 'index'])->name('api.v1.clients.index');
        Route::get('events', [EventController::class, 'index'])->name('api.v1.events.index');
        Route::get('products', [ProductController::class, 'index'])->name('api.v1.products.index');
    });
});
