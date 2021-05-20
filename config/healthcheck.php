<?php

return [
    'endpoint' => env('HEALTHCHECK_ENDPOINT', '/api/health'),
    'middleware' => env('HEALTHCHECK_MIDDLEWARE', 'healthcheck.auth'),
    'max_execution_time' => env('HEALTHCHECK_MAX_EXECUTION_TIME', 30),
    'redis_key' => env('HEALTHCHECK_REDIS_CHECK_KEY', 'redis-health-status'),
    'db_table' => env('HEALTHCHECK_DB_TABLE', 'healthcheck'),
    'auth_api_token' => env('HEALTHCHECK_API_TOKEN', 'health_api_token')
];
