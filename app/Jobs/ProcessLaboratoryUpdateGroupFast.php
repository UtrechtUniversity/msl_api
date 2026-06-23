<?php

namespace App\Jobs;

use App\Clients\Fast\Fast;
use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Laboratory\LaboratoryEquipmentAddon;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryUpdateFast;
use App\Models\LaboratoryUpdateGroupFast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryUpdateGroupFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast;

    /**
     * Create a new job instance.
     */
    public function __construct(LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast)
    {
        $this->laboratoryUpdateGroupFast = $laboratoryUpdateGroupFast;
    }

    /**
     * Execute the job.
     */
    public function handle(Fast $fast): void
    {
        // First, delete all existing laboratory data. So it synchs with CKAN.
        $laboratories = Laboratory::all();
        foreach ($laboratories as $laboratory) {
            $laboratory->delete();
        }

        $equipment = LaboratoryEquipment::all();
        foreach ($equipment as $equipmentRow) {
            $equipmentRow->delete();
        }

        // Truncating will reset primary keys etc. and delete all content. This will however not trigger delete events.
        LaboratoryOrganization::truncate();
        LaboratoryContactPerson::truncate();
        LaboratoryManager::truncate();
        LaboratoryEquipment::truncate();
        LaboratoryEquipmentAddon::truncate();
        Laboratory::truncate();

        // Retrieve results for facilities with EPOS-MSL tag and process results.
        $facilitiesResult = $fast->facilitiesRequest();

        $this->processResults($facilitiesResult, $fast);
    }

    /**
     * Create jobs to process fast API request results. When more pages with results are available process them too.
     */
    private function processResults($result, Fast $fast): void
    {
        if ($result->response_code == 200) {
            if (count($result->response_body['data']) > 0) {
                foreach ($result->response_body['data'] as $data) {
                    $laboratoryUpdateFast = LaboratoryUpdateFast::create([
                        'laboratory_update_group_fast_id' => $this->laboratoryUpdateGroupFast->id,
                        'laboratory_id' => $data['id'],
                    ]);

                    ProcessLaboratoryUpdateFast::dispatch($laboratoryUpdateFast);
                }

                if ($result->response_body['page']['current'] < $result->response_body['page']['last']) {
                    $facilitiesResult = $fast->facilitiesRequest($result->response_body['page']['next']);
                    $this->processResults($facilitiesResult, $fast);
                }
            }
        }
    }
}
