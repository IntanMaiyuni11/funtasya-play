<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
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
        // Gunakan styling Tailwind untuk pagination
        Paginator::useTailwind();

        // Paksa HTTPS jika aplikasi berjalan di server (Production/Vercel)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}