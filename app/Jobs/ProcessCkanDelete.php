<?php

namespace App\Jobs;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\DatasetPurgeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCkanDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $ckanIdentifier;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ckanIdentifier)
    {
        $this->ckanIdentifier = $ckanIdentifier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $ckanClient = new Client;
        $DatasetPurgeRequest = new DatasetPurgeRequest;
        $DatasetPurgeRequest->id = $this->ckanIdentifier;

        $ckanClient->get($DatasetPurgeRequest);
    }
}
