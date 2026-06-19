<?php

declare(strict_types=1);

use App\Http\Controllers\DownloadFileController;
use App\Http\Middleware\EnsureDistributorIsActive;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|\Illuminate\Contracts\View\View => view('welcome'));

Route::middleware(['auth:web', EnsureDistributorIsActive::class])
    ->name('dashboard.')
    ->group(function (): void {
        Route::get('files/{file}/download', DownloadFileController::class)
            ->name('files.download');
    });

Route::middleware('auth:staff')
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('files/{file}/download', DownloadFileController::class)
            ->name('files.download');
    });
