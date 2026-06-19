<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::disk('local')->makeDirectory('livewire-tmp');
        Storage::disk(File::DISK)->makeDirectory('');
    }
}
