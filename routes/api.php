<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\PaymentMethodController;
use App\Http\Controllers\Api\V1\ProductCategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('clients', [ClientController::class, 'index'])->name('api.v1.clients.index');
        Route::post('clients', [ClientController::class, 'store'])->can('create', Client::class)->name('api.v1.clients.store');
        Route::patch('clients/{client}', [ClientController::class, 'update'])->can('update', 'client')->name('api.v1.clients.update');

        Route::get('events', [EventController::class, 'index'])->name('api.v1.events.index');

        Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('api.v1.payment-methods.index');
        Route::post('payment-methods', [PaymentMethodController::class, 'store'])->can('create', PaymentMethod::class)->name('api.v1.payment-methods.store');
        Route::patch('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->can('update', 'paymentMethod')->name('api.v1.payment-methods.update');

        Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('api.v1.product-categories.index');

        Route::get('products', [ProductController::class, 'index'])->name('api.v1.products.index');
        Route::post('products', [ProductController::class, 'store'])->can('create', Product::class)->name('api.v1.products.store');
        Route::patch('products/{product}', [ProductController::class, 'update'])->can('update', 'product')->name('api.v1.products.update');
    });
});
