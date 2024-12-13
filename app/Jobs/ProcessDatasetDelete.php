<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\DatasetPurgeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DatasetDelete;

class ProcessDatasetDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $datasetDelete;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DatasetDelete $datasetDelete)
    {
        $this->datasetDelete = $datasetDelete;
    }    

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ckanClient = new Client();
        $DatasetPurgeRequest = new DatasetPurgeRequest();
        $DatasetPurgeRequest->id = $this->datasetDelete->ckan_id;

        $response = $ckanClient->get($DatasetPurgeRequest);
                        
        $this->datasetDelete->response_code = $response->responseCode;
        $this->datasetDelete->processed = now();
        $this->datasetDelete->save();        
    }
}
