<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (config('app.force_https') && ! $this->shouldSkipHttpsForAdminIpRequest()) {
            URL::forceScheme('https');
        }

        Storage::disk('local')->makeDirectory('livewire-tmp');
        Storage::disk(File::DISK)->makeDirectory('');
    }

    private function shouldSkipHttpsForAdminIpRequest(): bool
    {
        if ($this->app->runningInConsole()) {
            return false;
        }

        $host = request()->getHost();

        if (filter_var($host, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return request()->is('admin', 'admin/*', 'livewire/*');
    }
}
