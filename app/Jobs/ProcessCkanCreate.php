<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageCreateRequest;
use App\CkanClient\Request\PackageShowRequest;
use App\CkanClient\Request\PackageUpdateRequest;
use App\Models\LaboratoryCreate;
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
        $packageShowRequest->id = $this->model->name;

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

        $response = $client->get($packageCreateRequest);
    }

    private function updateLaboratory(Client $client)
    {
        $packageUpdateRequest = new PackageUpdateRequest;
        $packageUpdateRequest->payload = $this->model->tosearchableArray();

        $response = $client->get($packageUpdateRequest);
    }
}
