<?php

namespace App\Middleware;

use App\Jobs\ProcessMatomoPageTrackingJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiTrackingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Config::boolean('matomo.enabled')) {
            ProcessMatomoPageTrackingJob::dispatch($request->getClientIp(), $request->fullUrl());
        }

        return $next($request);
    }
}
