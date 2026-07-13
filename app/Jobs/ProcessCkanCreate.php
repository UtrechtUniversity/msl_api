<?php

namespace App\Jobs;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\PackageCreateRequest;
use App\Clients\CkanClient\Request\PackageShowRequest;
use App\Clients\CkanClient\Request\PackageUpdateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCkanCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ckanClient = new Client;
        $packageShowRequest = new PackageShowRequest;
        $packageShowRequest->id = (string)$this->model->id;

        $response = $ckanClient->get($packageShowRequest);

        if ($response->isSuccess()) {
            $this->updateLaboratory($ckanClient);
        } else {
            $this->createLaboratory($ckanClient);
        }
    }

    private function createLaboratory(Client $client)
    {
        $packageCreateRequest = new PackageCreateRequest;
        $packageCreateRequest->payload = $this->model->toSearchableArray();

        $client->get($packageCreateRequest);
    }

    private function updateLaboratory(Client $client)
    {
        $packageUpdateRequest = new PackageUpdateRequest;
        $packageUpdateRequest->payload = $this->model->tosearchableArray();

        $client->get($packageUpdateRequest);
    }
}
