<?php

namespace App\Providers;

use App\Models\Personnel;
use App\Observers\PersonnelObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Personnel::observe(PersonnelObserver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Blade::component('main-button', \App\View\Components\MainButton::class);
    }
}
