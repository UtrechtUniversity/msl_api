<?php

namespace App\Jobs;

use App\Clients\Fast\Fast;
use App\Models\Keyword;
use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Laboratory\LaboratoryEquipmentAddon;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryUpdateFast;
use App\Models\Vocabulary;
use App\Services\FastHarvestingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryUpdateFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LaboratoryUpdateFast $laboratoryUpdateFast;

    /**
     * Create a new job instance.
     */
    public function __construct(LaboratoryUpdateFast $laboratoryUpdateFast)
    {
        $this->laboratoryUpdateFast = $laboratoryUpdateFast;
    }

    /**
     * Execute the job.
     */
    public function handle(FastHarvestingService $service): void
    {
        $service->processFacilityUpdate($this->laboratoryUpdateFast);
    }

    /**
     * Attempt to locate keyword based upon equipment group, type and name
     */
    private function getEquipmentKeyword($equipment)
    {
        $vocabulary = Vocabulary::where('name', 'fast')->where('version', '1.0')->first();

        // Get keywords that match based on the name value of the equipment
        $nameKeywords = Keyword::where('vocabulary_id', $vocabulary->id)->where('value', $equipment->name)->get();

        /**
         * If we find 1 match we can assume the correct keyword is found. Otherwise traverse the vocabulary up
         * and check parent keywords to see if we found the correct one.
         */
        if ($nameKeywords->count() == 0) {
            return null;
        } elseif ($nameKeywords->count() == 1) {
            return $nameKeywords->first()->id;
        } else {
            foreach ($nameKeywords as $nameKeyword) {
                $GroupKeyword = $nameKeyword->parent;
                if ($GroupKeyword->value == $equipment->group_name) {
                    $typeKeyword = $GroupKeyword->parent;
                    if ($typeKeyword->value == $equipment->type_name) {
                        $nodeKeyword = $typeKeyword->parent;
                        if ($nodeKeyword->value == 'Equipment') {
                            $domainKeyword = $nodeKeyword->parent;
                            if ($domainKeyword->value == $equipment->domain_name) {
                                return $nameKeyword->id;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Attempt to locate keyword based on received add-on information
     */
    private function getAddonKeyword($addon, $equipment)
    {
        $vocabulary = Vocabulary::where('name', 'fast')->where('version', '1.0')->first();

        // Get keywords that match based on the group value of the equipment
        $groupKeywords = Keyword::where('vocabulary_id', $vocabulary->id)->where('value', $addon->group)->get();

        /**
         * If we find 1 match we can assume the correct keyword is found. Otherwise traverse the vocabulary up
         * and check parent keywords to see if we found the correct one.
         */
        if ($groupKeywords->count() == 0) {
            return null;
        } elseif ($groupKeywords->count() == 1) {
            return $groupKeywords->first()->id;
        } else {
            foreach ($groupKeywords as $groupKeyword) {
                $typeKeyword = $groupKeyword->parent;
                if ($typeKeyword->value == $addon->type) {
                    $nodeKeyword = $typeKeyword->parent;
                    if ($nodeKeyword->value == 'Add-ons') {
                        $domainKeyword = $nodeKeyword->parent;
                        if ($domainKeyword->value == $equipment->domain_name) {
                            return $groupKeyword->id;
                        }
                    }
                }

            }
        }
    }
}
