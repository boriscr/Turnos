<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('es');
        date_default_timezone_set('America/Argentina/Jujuy');
    }
    /**
     * Bootstrap any application services.
     */
}
