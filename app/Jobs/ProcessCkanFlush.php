<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCkanFlush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $ckanType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ckanType)
    {
        $this->ckanType = $ckanType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $ckanClient = new Client;
        $searchRequest = new PackageSearchRequest();

        $searchRequest->addFilterQuery('type', $this->ckanType);
        $searchRequest->start = 0;
        $searchRequest->rows = 1000;

        $result = $ckanClient->get($searchRequest);
        $ids = collect($result->getResults())->pluck('name');

        while(($searchRequest->start + $searchRequest->rows) < $result->getTotalResultsCount()) {
            $searchRequest->start = $searchRequest->start + $searchRequest->rows;
            $result = $ckanClient->get($searchRequest);

            $ids = $ids->merge(collect($result->getResults())->pluck('name'));
        }

        $ids->each(function ($id) {
            ProcessCkanDelete::dispatch($id);
        });

    }
}
