<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

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
        // Disable the automatic 'data' wrapper so Inertia receives flat arrays
        // from JsonResource instances instead of { data: { ... } } objects.
        JsonResource::withoutWrapping();
    }
}
