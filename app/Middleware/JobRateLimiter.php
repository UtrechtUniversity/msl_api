<?php

namespace App\Middleware;

use Illuminate\Support\Facades\Cache;

class JobRateLimiter
{
    public function handle($job, $next)
    {
        // Get groupname from job to set a lock for the group of jobs
        $jobGroup = $job->getGroupName();

        // Create a cache lock for 5 seconds
        $lock = Cache::lock($jobGroup, 5);

        // Trying to get a lock and fire a job (if 5 seconds passed)
        if ($lock->get()) {
            return $next($job);
        }

        // Send a job back to the queue if the lock can't acquired
        return $job->release();
    }
}
