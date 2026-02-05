<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MatomoTracker;

class ProcessMatomoDownloadTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $ip;

    protected string $uri;

    public function __construct(string $ip, string $uri)
    {
        $this->ip = $ip;
        $this->uri = $uri;
    }

    public function handle(): void
    {
        $matomo = new MatomoTracker((int)config('matomo.site_id'), config('matomo.host'));
        $matomo->setTokenAuth(config('matomo.token'));
        $matomo->setIp($this->ip);
        $matomo->doTrackAction($this->uri, 'download');
    }
}
