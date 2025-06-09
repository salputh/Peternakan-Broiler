<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Peternakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Kandangs;


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
        //
        Blade::component('developer.layouts.app', 'app-layout');

        View::composer('layouts.app', function ($view) {
            $routePeternakan = request()->route('peternakan');

            // Jika hasil route sudah model, langsung pakai
            $peternakan = $routePeternakan instanceof Peternakan
                ? $routePeternakan
                : null;

            $view->with('peternakan', $peternakan);
        });

        Route::bind('peternakan', function ($value) {
            return Peternakan::where('slug', $value)->firstOrFail();
        });

        Route::bind('kandang', function ($value) {
            return Kandangs::where('slug', $value)->firstOrFail();
        });
    }
}
