<?php

namespace Gftech\Healthcheck;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\Redis;

/**
 * Class HealthCheckServiceInterface
 *
 * Сервис для мониторинга работает ли БД, редис и тд. Будет дергаться из вне
 *
 * @package App\Services\HealthCheck
 */
interface HealthCheckServiceInterface
{
    /**
     * @return array
     * @throws Exception
     */
    public function run(): array;
}
