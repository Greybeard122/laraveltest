<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
{
    /* if (env('APP_ENV') === 'production') { // Only enforce HTTPS in production
        URL::forceScheme('https'); */
        if (env('APP_ENV') === 'production') { // Force HTTPS in production
            URL::forceScheme('https');
        }

    

    Log::info('Application boot completed');
    Paginator::useTailwind();
}

}
