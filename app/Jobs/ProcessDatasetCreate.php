<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DatasetCreate;
use App\CkanClient\Client;
use App\CkanClient\Request\PackageCreateRequest;
use App\CkanClient\Request\PackageShowRequest;
use App\CkanClient\Request\PackageUpdateRequest;

class ProcessDatasetCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $datasetCreate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DatasetCreate $datasetCreate)
    {
        $this->datasetCreate = $datasetCreate;
    }
            

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ckanClient = new Client();
        
        $packageShowRequest = new PackageShowRequest();
        $packageShowRequest->id = $this->datasetCreate->dataset['name'];
        
        //check if package is already in ckan
        $response = $ckanClient->get($packageShowRequest);
        
        if($response->isSuccess()) {
            $this->updateDataset($ckanClient);
        } else {
            $this->createDataset($ckanClient);
        }                          
    }
    
    /**
     * Send package create request to ckan and update datasetcreate record in database
     * 
     * @return void
     */
    private function createDataset($client)
    {
        $packageCreateRequest = new PackageCreateRequest();
        $packageCreateRequest->payload = $this->datasetCreate->dataset;

        $response = $client->get($packageCreateRequest);        
        
        $this->datasetCreate->response_code = $response->responseCode;
        $this->datasetCreate->response_body = json_encode($response->responseBody);
        $this->datasetCreate->processed_type = 'insert'; 
        $this->datasetCreate->processed = now();
        $this->datasetCreate->save();
    }
    
    /**
     * Send package update request to ckan and update datasetcreate record in database
     * 
     * @return void
     */
    private function updateDataset($client)
    {
        $packageUpdateRequest = new PackageUpdateRequest();
        $packageUpdateRequest->payload = $this->datasetCreate->dataset;

        $response = $client->get($packageUpdateRequest);

        $this->datasetCreate->response_code = $response->responseCode;
        $this->datasetCreate->response_body = json_encode($response->responseBody);
        $this->datasetCreate->processed_type = 'update';
        $this->datasetCreate->processed = now();
        $this->datasetCreate->save();
    }
}
