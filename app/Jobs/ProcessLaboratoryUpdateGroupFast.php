<?php

namespace App\Jobs;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Fast\Fast;
use App\Models\DatasetDelete;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Laboratory;
use App\Models\LaboratoryUpdateGroupFast;
use App\Models\LaboratoryUpdateFast;
use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryContactPerson;
use App\Models\LaboratoryManager;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentAddon;


class ProcessLaboratoryUpdateGroupFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $laboratoryUpdateGroupFast;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast)
    {
        $this->laboratoryUpdateGroupFast = $laboratoryUpdateGroupFast;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Remove current data based upon last FAST update
        LaboratoryOrganization::truncate();
        LaboratoryContactPerson::truncate();
        LaboratoryManager::truncate();
        LaboratoryEquipment::truncate();
        LaboratoryEquipmentAddon::truncate();
        Laboratory::truncate();

        // Retrieve all fast lab ids using API
        $fast =  new Fast();

        // Retrieve results for facilities with EPOS-MSL tag and process results.
        $facilitiesResult = $fast->facilitiesRequest();

        $this->processResults($facilitiesResult, $fast);
    }

    /**
     * Create jobs to process fast API request results. When more pages with results are available process them too.
     * 
     * @return void
     */
    private function processResults($result, $fast)
    {
        if($result->response_code == 200) {
            if(count($result->response_body['data']) > 0) {
                foreach($result->response_body['data'] as $data) {
                    $laboratoryUpdateFast = LaboratoryUpdateFast::create([
                        'laboratory_update_group_fast_id' => $this->laboratoryUpdateGroupFast->id,
                        'laboratory_id' => $data['id']
                    ]);
                    
                    ProcessLaboratoryUpdateFast::dispatch($laboratoryUpdateFast);
                }

                if($result->response_body['page']['current'] < $result->response_body['page']['last']) {
                    $facilitiesResult = $fast->facilitiesRequest($result->response_body['page']['next']);
                    $this->processResults($facilitiesResult, $fast);
                }
            }
        }
    }
    
}
