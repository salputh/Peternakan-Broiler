<?php

declare(strict_types=1);

namespace BladeUI\Emojis;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeEmojisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-emojis', []);

            $factory->add('emojis', array_merge(['path' => __DIR__.'/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/blade-emojis.php', 'blade-emojis');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/blade-emojis'),
            ], 'blade-emojis');

            $this->publishes([
                __DIR__.'/../config/blade-emojis.php' => $this->app->configPath('blade-emojis.php'),
            ], 'blade-emojis-config');
        }
    }
}
