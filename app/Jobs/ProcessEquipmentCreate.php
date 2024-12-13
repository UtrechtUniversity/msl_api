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
use App\Models\EquipmentCreate;

class ProcessEquipmentCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $equipmentCreate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EquipmentCreate $equipmentCreate)
    {
        $this->equipmentCreate = $equipmentCreate;
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
        $packageShowRequest->id = $this->equipmentCreate->equipment['name'];

        $response = $ckanClient->get($packageShowRequest);

        if($response->isSuccess()) {
            $this->updateEquipment($ckanClient);
        } else {
            $this->createEquipment($ckanClient);
        }                                  
    }
    
    private function createEquipment(Client $client)
    {
        $packageCreateRequest = new PackageCreateRequest();
        $packageCreateRequest->payload = $this->equipmentCreate->equipment;
        
        $response = $client->get($packageCreateRequest);

        $this->equipmentCreate->response_code = $response->responseCode;
        $this->equipmentCreate->processed_type = 'insert';
        $this->equipmentCreate->processed = now();
        $this->equipmentCreate->save();
    }
    
    private function updateEquipment(Client $client)
    {
        $packageUpdateRequest = new PackageUpdateRequest();
        $packageUpdateRequest->payload = $this->equipmentCreate->equipment;

        $response = $client->get($packageUpdateRequest);

        $this->equipmentCreate->response_code = $response->responseCode;
        $this->equipmentCreate->processed_type = 'update';
        $this->equipmentCreate->processed = now();
        $this->equipmentCreate->save();        
    }
}
