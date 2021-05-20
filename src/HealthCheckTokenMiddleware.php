<?php

namespace Gftech\Healthcheck;

use Closure;
use Illuminate\Http\Request;

/**
 * Базовая мидлваря для проверки токена авторизации
 *
 * @package App\Services\HealthCheck
 */
class HealthCheckTokenMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('token') !== config('healthcheck.auth_api_token')) {
            return abort(403, 'Wrong api token');
        }

        return $next($request);
    }
}
