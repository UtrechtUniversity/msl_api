<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Models\DatasetDelete;
use App\Models\EquipmentCreate;
use App\Models\Laboratory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Seed;
use App\Models\OrganizationCreate;
use App\Models\LaboratoryCreate;
use App\Models\LaboratoryEquipment;

class ProcessSeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $seed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Seed $seed)
    {
        $this->seed = $seed;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $seeder = $this->seed->seeder;
        
        if($seeder->type == "organization") {
            if($seeder->options['type'] == 'fileSeeder') {
                $filePath = $seeder->options['filePath'];
                
                if(Storage::disk()->exists($filePath)) {
                    $jsonEntries = json_decode(Storage::get($filePath), true);
                    
                    foreach ($jsonEntries as $jsonEntry) {
                        $organizationCreate = OrganizationCreate::create([
                            'organization_type' => 'organization',
                            'organization' => $jsonEntry,
                            'seed_id' => $this->seed->id
                        ]);
                        
                        ProcessOrganizationCreate::dispatch($organizationCreate);
                    }
                }
            }
        } elseif ($seeder->type == "lab") {
            // create jobs to delete all currently stored labs from ckan
            $ckanClient = new Client();

            $searchRequest = new PackageSearchRequest();
            $searchRequest->addFilterQuery("type", "lab");
            $searchRequest->rows = 1000;

            $result = $ckanClient->get($searchRequest);
            $results = $result->getResults();

            foreach($results as $result) {
                $datasetDelete = DatasetDelete::create([
                    'ckan_id' => $result['id']
                ]);
                
                ProcessDatasetDelete::dispatch($datasetDelete);
            }

            // create jobs to add labs to ckan
            $laboratories = Laboratory::get();            

            foreach($laboratories as $laboratory) {
                $LaboratoryCreate = LaboratoryCreate::create([
                    'laboratory_type' => 'laboratory',
                    'laboratory' => $laboratory->toCkanArray(),
                    'seed_id' => $this->seed->id
                ]);
                
                ProcessLaboratoryCreate::dispatch($LaboratoryCreate);
            }
        } elseif($seeder->type == "equipment") {
            // create jobs to delete all currently stored equipment from ckan
            $ckanClient = new Client();

            $searchRequest = new PackageSearchRequest();
            $searchRequest->addFilterQuery("type", "equipment");
            $searchRequest->rows = 1000;

            $result = $ckanClient->get($searchRequest);
            $results = $result->getResults();

            foreach($results as $result) {
                $datasetDelete = DatasetDelete::create([
                    'ckan_id' => $result['id']
                ]);
                
                ProcessDatasetDelete::dispatch($datasetDelete);
            }

            // create jobs to add equipment to ckan
            $equipmentList = LaboratoryEquipment::get();
            foreach($equipmentList as $equipment) {
                $equipmentCreate = EquipmentCreate::create([
                    'equipment_type' => 'equipment',
                    'equipment' => $equipment->toCkanArray(),
                    'seed_id' => $this->seed->id
                ]);

                ProcessEquipmentCreate::dispatch($equipmentCreate);
            }

        } else {
            throw new \Exception('Invalid Seeder configuration.');
        }

    }    
}
