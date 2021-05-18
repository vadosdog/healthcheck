<?php

return [
    'endpoint' => env('HEALTHCHECK_ENDPOINT', '/api/health'),
    'middleware' => env('HEALTHCHECK_MIDDLEWARE', 'auth:api'),
    'max_execution_time' => env('HEALTHCHECK_MAX_EXECUTION_TIME', 30),
    'redis_key' => env('HEALTHCHECK_REDIS_CHECK_KEY', 'redis-health-status'),
    'db_table' => env('HEALTHCHECK_DB_TABLE', 'healthcheck'),
];
