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
    public const DATABASE_LOCKS_COUNT_LIMIT = 5;
    public const DATABASE_ACTIVITY_COUNT_LIMIT = 20;

    /**
     * @return array
     * @throws Exception
     */
    public function run(): array
    {
        // Бьем тревогу, если не отвечает в течение этого времени
        ini_set('max_execution_time', config('healthcheck.max_execution_time', 30));

        $result = $this->check();

        Log::info('Health check success', ['component' => 'health_check']);

        return $result;
    }

    protected function check(): array
    {
        return [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'redispersist' => $this->checkRedisPersist(),
            'database_locks' => $this->getDatabaseLocks(),
            'database_activity' => $this->getDatabaseActivity(),
            'graylog' => true
        ];
    }

    /**
     * Для проверки работает ли БД просто выполним запрос
     *
     * @return bool
     * @throws Exception
     */
    protected function checkDatabase(): bool
    {
        $table = config('healthcheck.db_table', 'healthcheck');
        $result = DB::select("INSERT INTO $table (id) VALUES (1) ON CONFLICT (id) DO UPDATE SET id = 1");

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
    protected function checkRedis(): bool
    {
        Cache::put(config('healthcheck.redis_key', 'redis-health-status'), self::REDIS_CHECK_VALUE, 60);
        $result = Cache::get(config('healthcheck.redis_key', 'redis-health-status'));

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
    protected function checkRedisPersist(): bool
    {
        Redis::connection('default')->set(config('healthcheck.redis_key', 'redis-health-status'), self::REDIS_CHECK_VALUE);
        $result = Redis::connection('default')->get(config('healthcheck.redis_key', 'redis-health-status'));

        if (empty($result)) {
            throw new Exception('Empty result for checkRedisPersist');
        } else {
            return true;
        }
    }

    /**
     * Возвращает количество локов в бд
     *
     * @return bool
     */
    protected function getDatabaseLocks(): bool
    {
        $result = DB::select("
            select pg_locks.*
            from pg_locks
            left join pg_stat_activity on pg_stat_activity.pid = pg_locks.pid
            where locktype = 'advisory'
            and granted = true
            and pg_stat_activity.state_change - pg_stat_activity.backend_start > interval '20 second';
        ");

        return count($result) <= self::DATABASE_LOCKS_COUNT_LIMIT;
    }

    /**
     * Проверяем количество активных запросов в БД
     *
     * @return bool
     */
    protected function getDatabaseActivity(): bool
    {
        $result = DB::select("
            SELECT pid, age(query_start, clock_timestamp()), usename, query,wait_event_type
            FROM pg_stat_activity
            WHERE query != '<IDLE>' AND
                    query NOT ILIKE '%pg_stat_activity%'  and state != 'idle'
        ");

        return count($result) <= self::DATABASE_ACTIVITY_COUNT_LIMIT;
    }
}
