<?php

namespace Gftech\Healthcheck;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class HealthCheckServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/healthcheck.php' => App::configPath('healthcheck.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/healthcheck.php', 'healthcheck'
        );

        $this->publishes([
            __DIR__ . '/../migrations' => App::databasePath('migrations'),
        ], 'migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('healthcheck.auth', HealthCheckTokenMiddleware::class);
    }

    public function register()
    {
        $this->app->bind(HealthCheckServiceInterface::class, HealthCheckService::class);
    }
}
