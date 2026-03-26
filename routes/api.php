<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('user', fn (Request $request) => $request->user())->name('api.v1.user');
    });
});
