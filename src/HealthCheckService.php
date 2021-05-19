<?php

namespace Gftech\Healthcheck;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\Redis;

/**
 * Class HealthCheckService
 *
 * Сервис для мониторинга работает ли БД, редис и тд. Будет дергаться из вне
 *
 * @package App\Services\HealthCheck
 */
class HealthCheckService implements HealthCheckServiceInterface
{
    public const REDIS_CHECK_VALUE = 1;

    /**
     * @return array
     * @throws Exception
     */
    public function run(): array
    {
        // Бьем тревогу, если не отвечает в течение этого времени
        ini_set('max_execution_time', config('healthcheck.max_execution_time'));

        $result = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'redispersist' => $this->checkRedisPersist(),
            'database_locks' => $this->getDatabaseLocks()
        ];

        Log::info('Health check success', ['component' => 'health_check']);

        $result['graylog'] = true;

        return $result;
    }

    /**
     * Для проверки работает ли БД просто выполним запрос
     *
     * @return bool
     * @throws Exception
     */
    private function checkDatabase(): bool
    {
        $result = DB::select("INSERT INTO healthcheck (id) VALUES (1) ON CONFLICT (id) DO UPDATE SET id = 1");

        if (empty($result)) {
            throw new Exception('Empty result for checkDatabase');
        } else {
            return true;
        }
    }

    /**
     * Для проверки работает ли редис просто положим и достанем из него значение
     *
     * @return bool
     * @throws Exception
     */
    private function checkRedis(): bool
    {
        Cache::put(config('healthcheck.redis_key'), self::REDIS_CHECK_VALUE, 60);
        $result = Cache::get(config('healthcheck.redis_key'));

        if (empty($result)) {
            throw new Exception('Empty result for checkRedis');
        } else {
            return true;
        }
    }

    /**
     * Для проверки работает ли второй редис просто положим и достанем из него значение
     *
     * @return bool
     * @throws Exception
     */
    private function checkRedisPersist(): bool
    {
        Redis::connection('default')->set(config('healthcheck.redis_key'), self::REDIS_CHECK_VALUE);
        $result = Redis::connection('default')->get(config('healthcheck.redis_key'));

        if (empty($result)) {
            throw new Exception('Empty result for checkRedisPersist');
        } else {
            return true;
        }
    }

    /**
     * Возвращает количество локов в бд
     *
     * @return int
     */
    private function getDatabaseLocks(): int
    {
        $result = DB::select("
            select pg_locks.*
            from pg_locks
            left join pg_stat_activity on pg_stat_activity.pid = pg_locks.pid
            where locktype = 'advisory'
            and granted = true
            and pg_stat_activity.state_change - pg_stat_activity.backend_start > interval '20 second';
        ");

        return count($result);
    }
}
