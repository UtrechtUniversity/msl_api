<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\DatasetPurgeRequest;
use App\CkanClient\Request\PackageCreateRequest;
use App\CkanClient\Request\PackageShowRequest;
use App\CkanClient\Request\PackageUpdateRequest;
use App\Models\LaboratoryCreate;
use App\Scout\CkanSearchableInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCkanDelete implements ShouldQueue
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
    public function handle(): void
    {
        $ckanClient = new Client;
        $DatasetPurgeRequest = new DatasetPurgeRequest;
        $DatasetPurgeRequest->id = $this->model->toSearchableArray()['name'];;

        $ckanClient->get($DatasetPurgeRequest);
    }
}
