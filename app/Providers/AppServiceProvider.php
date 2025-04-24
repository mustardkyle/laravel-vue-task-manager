<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is used to bind services into the container.
     */
    public function register(): void
    {
        // Register application services here if needed.
    }

    /**
     * Bootstrap any application services.
     *
     * This method is used to perform any actions required during application bootstrapping.
     */
    public function boot(): void
    {
        // Set a default string length for database schema to avoid issues with older MySQL versions.
        Schema::defaultStringLength(191);
    }
}