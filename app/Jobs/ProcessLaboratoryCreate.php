<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\CkanClient\Client;
use App\CkanClient\Request\PackageCreateRequest;
use App\CkanClient\Request\PackageShowRequest;
use App\CkanClient\Request\PackageUpdateRequest;
use App\Models\LaboratoryCreate;

class ProcessLaboratoryCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $laboratoryCreate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryCreate $laboratoryCreate)
    {
        $this->laboratoryCreate = $laboratoryCreate;
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
        $packageShowRequest->id = $this->laboratoryCreate->laboratory['name'];

        $response = $ckanClient->get($packageShowRequest);

        if($response->isSuccess()) {
            $this->updateLaboratory($ckanClient);
        } else {
            $this->createLaboratory($ckanClient);
        }                                  
    }
    
    private function createLaboratory(Client $client)
    {
        $packageCreateRequest = new PackageCreateRequest();
        $packageCreateRequest->payload = $this->laboratoryCreate->laboratory;
        
        $response = $client->get($packageCreateRequest);

        $this->laboratoryCreate->response_code = $response->responseCode;
        $this->laboratoryCreate->processed_type = 'insert';
        $this->laboratoryCreate->processed = now();
        $this->laboratoryCreate->save();
    }
    
    private function updateLaboratory(Client $client)
    {
        $packageUpdateRequest = new PackageUpdateRequest();
        $packageUpdateRequest->payload = $this->laboratoryCreate->laboratory;

        $response = $client->get($packageUpdateRequest);

        $this->laboratoryCreate->response_code = $response->responseCode;
        $this->laboratoryCreate->processed_type = 'update';
        $this->laboratoryCreate->processed = now();
        $this->laboratoryCreate->save();        
    }
}
