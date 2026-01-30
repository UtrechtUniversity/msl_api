<?php

namespace App\Middleware;

use App\Jobs\ProcessMatomoTrackingJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiTrackingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(Config::boolean('matomo.enabled')) {
            ProcessMatomoTrackingJob::dispatch($request->getClientIp(), $request->getRequestUri());
        }

        return $next($request);
    }
}
