<?php

namespace Gftech\Healthcheck;

use Illuminate\Support\ServiceProvider;

class HealthCheckServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
            __DIR__ . '/../config/healthcheck.php' => config_path('healthcheck.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/healthcheck.php', 'healthcheck'
        );

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make(HealthCheckController::class);
    }
}
