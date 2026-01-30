<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use MatomoTracker;

class ApiTrackingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(Config::boolean('matomo.enabled')) {
            $matomo = new MatomoTracker((int)config('matomo.site_id'), config('matomo.host'));
            $matomo->setTokenAuth(config('matomo.token'));
            $matomo->setIp($request->getClientIp());
            $matomo->doTrackPageView($request->getRequestUri());
        }

        return $next($request);
    }
}
