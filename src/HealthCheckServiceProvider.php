<?php

namespace Gftech\Healthcheck;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class HealthCheckServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/healthcheck.php' => App::configPath('healthcheck.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../migrations' => App::databasePath('migrations'),
        ], 'migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
