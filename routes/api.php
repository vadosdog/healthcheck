<?php

use Gftech\Healthcheck\HealthCheckController;

Route::get(config('healthcheck.endpoint', '/api/health'), [HealthCheckController::class, 'index'])
    ->middleware(config('healthcheck.middleware', 'healthcheck.auth'));
