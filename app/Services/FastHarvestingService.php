<?php

namespace App\Services;

use App\Clients\Fast\Fast;
use App\Jobs\ProcessCkanFlush;
use App\Jobs\ProcessLaboratoryUpdateFast;
use App\Jobs\ProcessLaboratoryUpdateGroupFast;
use App\Mappers\Fast\FacilityResponseMapper;
use App\Models\Keyword;
use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Laboratory\LaboratoryEquipmentAddon;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryUpdateFast;
use App\Models\LaboratoryUpdateGroupFast;
use App\Models\Vocabulary;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\QueueableEntity;

class FastHarvestingService
{
    public function removeExistingData(ProcessLaboratoryUpdateGroupFast $job): void
    {
        // Truncating will reset primary keys etc. and delete all content. This will, however, not trigger deleted
        // events and thus not trigger removal from CKAN.
        LaboratoryOrganization::truncate();
        LaboratoryContactPerson::truncate();
        LaboratoryManager::truncate();
        LaboratoryEquipmentAddon::truncate();
        Laboratory::truncate();
        LaboratoryEquipment::truncate();

        // Flush all laboratory and equipment information from CKAN.
        // Prepend to queue to ensure that the flush is executed before the next job.
        $job->prependToChain(new ProcessCkanFlush('lab'));
        $job->prependToChain(new ProcessCkanFlush('equipment'));

        //ProcessCkanFlush::dispatch('lab');
        //ProcessCkanFlush::dispatch('equipment');
    }

    public function retrieveFastData(ProcessLaboratoryUpdateGroupFast $job): void
    {
        $fast = new Fast;
        $facilitiesResult = $fast->facilitiesRequest();

        $this->processFastFacilitiesResults($facilitiesResult, $fast, $job);
    }

    public function processFacilityUpdate($laboratoryUpdateFast): void
    {
        $fast = new Fast;
        $result = $fast->facilityRequest($laboratoryUpdateFast->laboratory_id);

        if ($result->response_code == 200) {
            $data = $result->response_body['data'];
            $laboratory = FacilityResponseMapper::mapToLaboratory($data);

            Laboratory::withoutSyncingToSearch(function () use ($laboratory) {
                $laboratory->save();
            });

            if (isset($data['affiliation'])) {
                $organization = LaboratoryOrganization::where('fast_id', $data['affiliation']['id'])->first();

                if(!$organization) {
                    $organization = FacilityResponseMapper::mapToOrganization($data['affiliation']);
                    $organization->saveQuietly();
                }

                $laboratory->laboratoryOrganization()->associate($organization);
            }

            if (isset($data['contact_persons'])) {
                foreach ($data['contact_persons'] as $contactPersonEmail) {
                    $contactPerson = FacilityResponseMapper::mapToContactPerson($contactPersonEmail);
                    $laboratory->laboratoryContactPersons()->saveQuietly($contactPerson);
                }
            }

            if (isset($data['managers'])) {
                foreach ($data['managers'] as $managerData) {
                    $manager = LaboratoryManager::where('fast_id', $managerData['id'])->first();

                    if (!$manager) {
                        $manager = FacilityResponseMapper::mapToManager($managerData);
                        $manager->saveQuietly();
                    }

                    $laboratory->laboratoryManager()->associate($manager);
                }
            }

            if (isset($data['equipment'])) {
                foreach ($data['equipment'] as $fastEquipment) {
                    $equipment = FacilityResponseMapper::mapToEquipment($fastEquipment);

                    $keyword = $this->getEquipmentKeyword($equipment);
                    if ($keyword) {
                        $equipment->keyword()->associate($keyword);
                    }

                    $equipment->laboratory()->associate($laboratory);

                    LaboratoryEquipment::withoutSyncingToSearch(function () use ($equipment) {
                        $equipment->save(['touch' => false]);
                    });

                    foreach ($fastEquipment['addons'] as $addon) {
                        if (isset($addon['description'])) {
                            $laboratoryEquipmentAddon = FacilityResponseMapper::mapToEquipmentAddon($addon);

                            $laboratoryEquipmentAddon->keyword()->associate($this->getAddonKeyword($laboratoryEquipmentAddon, $equipment));

                            $equipment->laboratoryEquipmentAddons()->saveQuietly($laboratoryEquipmentAddon);
                        }
                    }

                    $equipment->save(['touch' => false]);
                }
            }


            $laboratory->save();

            $laboratoryUpdateFast->response_code = $result->response_code;
            $laboratoryUpdateFast->source_laboratory_data = $data;
            $laboratoryUpdateFast->save();
        } else {
            $laboratoryUpdateFast->response_code = $result->response_code;
            $laboratoryUpdateFast->save();
        }


    }

    private function processFastFacilitiesResults($result, Fast $fast, ProcessLaboratoryUpdateGroupFast $job): void
    {
        if ($result->response_code == 200) {
            if (count($result->response_body['data']) > 0) {
                foreach ($result->response_body['data'] as $data) {
                    $laboratoryUpdateFast = LaboratoryUpdateFast::create([
                        'laboratory_update_group_fast_id' => $job->laboratoryUpdateGroupFast->id,
                        'laboratory_id' => $data['id'],
                    ]);

                    $job->appendToChain(new ProcessLaboratoryUpdateFast($laboratoryUpdateFast));
                }

                if ($result->response_body['page']['current'] < $result->response_body['page']['last']) {
                    $facilitiesResult = $fast->facilitiesRequest($result->response_body['page']['next']);
                    $this->processFastFacilitiesResults($facilitiesResult, $fast, $job);
                }
            }
        }
    }

    /**
     * Attempt to locate keyword based upon equipment group, type and name
     */
    private function getEquipmentKeyword(LaboratoryEquipment $equipment): ?Keyword
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
            return $nameKeywords->first();
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
                                return $nameKeyword;
                            }
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Attempt to locate keyword based on received add-on information
     */
    private function getAddonKeyword(LaboratoryEquipmentAddon $addon, LaboratoryEquipment $equipment): ?Keyword
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
            return $groupKeywords->first();
        } else {
            foreach ($groupKeywords as $groupKeyword) {
                $typeKeyword = $groupKeyword->parent;
                if ($typeKeyword->value == $addon->type) {
                    $nodeKeyword = $typeKeyword->parent;
                    if ($nodeKeyword->value == 'Add-ons') {
                        $domainKeyword = $nodeKeyword->parent;
                        if ($domainKeyword->value == $equipment->domain_name) {
                            return $groupKeyword;
                        }
                    }
                }
            }
        }

        return null;
    }

}
