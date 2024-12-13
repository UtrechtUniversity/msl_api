<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OrganizationCreate;
use App\CkanClient\Client;
use App\CkanClient\Request\OrganizationCreateRequest;
use App\CkanClient\Request\OrganizationShowRequest;
use App\CkanClient\Request\OrganizationUpdateRequest;

class ProcessOrganizationCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $organizationCreate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrganizationCreate $organizationCreate)
    {
        $this->organizationCreate = $organizationCreate;
    }
            

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();

        $organizationShowRequest = new OrganizationShowRequest();
        $organizationShowRequest->id = $this->organizationCreate->organization['name'];

        $response = $client->get($organizationShowRequest);

        if($response->isSuccess()) {
            $this->updateOrganization($client, $this->organizationCreate->organization['name']);
        } else {
            $this->createOrganization($client);
        }                
    }
    
    private function createOrganization($client)
    {
        $organizationCreateRequest = new OrganizationCreateRequest();
        $organizationCreateRequest->payload = $this->organizationCreate->organization;

        $response = $client->get($organizationCreateRequest);

        $this->organizationCreate->response_code = $response->responseCode;
        //$this->organizationCreate->response_body = json_encode($response->responseBody);
        $this->organizationCreate->processed_type = 'insert'; 
        $this->organizationCreate->processed = now();
        $this->organizationCreate->save();
    }
    
    private function updateOrganization($client, $id)
    {
        $OrganizationUpdateRequest = new OrganizationUpdateRequest();
        $OrganizationUpdateRequest->payload = $this->organizationCreate->organization;
        $OrganizationUpdateRequest->payload['id'] = $id;

        $response = $client->get($OrganizationUpdateRequest);
        
        $this->organizationCreate->response_code = $response->responseCode;
        //$this->organizationCreate->response_body = (string)$response->getBody();
        $this->organizationCreate->processed_type = 'update';
        $this->organizationCreate->processed = now();
        $this->organizationCreate->save();
    }
}
