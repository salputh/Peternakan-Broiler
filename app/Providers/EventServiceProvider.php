<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    protected $observers = [
        \App\Models\DataPeriode::class => [\App\Observers\DataPeriodeObserver::class],
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // app/Providers/EventServiceProvider.php

    }
}
