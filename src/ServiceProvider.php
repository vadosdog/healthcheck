<?php

namespace Gftech\Healthcheck;

use Dompdf\Dompdf;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        dd('tyt');
        $this->publishes([
            __DIR__ . '/../config/healthcheck.php' => config_path('healthcheck.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }

//    /**
//     * Get the services provided by the provider.
//     *
//     * @return array
//     */
//    public function provides()
//    {
//        return ['dompdf', 'dompdf.options', 'dompdf.wrapper'];
//    }
}
