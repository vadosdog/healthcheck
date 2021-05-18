<?php

use Gftech\Healthcheck\HealthCheckController;

Route::get(config('healthcheck.endpoint'), [HealthCheckController::class, 'index'])
    ->middleware(config('healthcheck.middleware'));
