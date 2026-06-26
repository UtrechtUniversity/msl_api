<?php

namespace App\Services;

use App\Clients\Fast\Fast;
use App\Jobs\ProcessCkanFlush;
use App\Jobs\ProcessLaboratoryUpdateFast;
use App\Mappers\Fast\FacilityResponseMapper;
use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Laboratory\LaboratoryEquipmentAddon;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryUpdateFast;
use App\Models\LaboratoryUpdateGroupFast;

class FastHarvestingService
{
    public function removeExistingData(): void
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
        ProcessCkanFlush::dispatch('lab');
        ProcessCkanFlush::dispatch('equipment');
    }

    public function retrieveFastData(LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast): void
    {
        $fast = new Fast;
        $facilitiesResult = $fast->facilitiesRequest();

        $this->processFastFacilitiesResults($facilitiesResult, $fast, $laboratoryUpdateGroupFast);
    }

    public function processFacilityUpdate(LaboratoryUpdateFast $laboratoryUpdateFast): void
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

            /*
            // include equipment
            if (isset($data['equipment'])) {
                foreach ($data['equipment'] as $fastEquipment) {
                    $equipment = new LaboratoryEquipment;

                    $equipment->fast_id = $fastEquipment['id'];
                    $equipment->laboratory_id = $lab->id;
                    $equipment->description = $fastEquipment['description'];

                    $equipment->description_html = '';
                    if (isset($fastEquipment['description_html'])) {
                        $equipment->description_html = $fastEquipment['description_html'];
                    }

                    $equipment->category_name = $fastEquipment['category']['name'];
                    $equipment->type_name = $fastEquipment['type']['name'];
                    $equipment->domain_name = $fastEquipment['type']['domain']['name'];
                    $equipment->group_name = $fastEquipment['group']['name'];
                    $equipment->brand = $fastEquipment['brand'];
                    $equipment->website = $fastEquipment['website'];
                    $equipment->latitude = $fastEquipment['gps_latitude'];
                    $equipment->longitude = $fastEquipment['gps_longitude'];
                    $equipment->altitude = $fastEquipment['gps_altitude'];

                    $equipment->external_identifier = '';
                    if (isset($fastEquipment['external_identifier'])) {
                        $equipment->external_identifier = $fastEquipment['external_identifier'];
                    }

                    $equipment->name = $fastEquipment['name']['name'];

                    // create reference to keyword
                    $equipment->keyword_id = $this->getEquipmentKeyword($equipment);

                    $equipment->save();

                    // add addons
                    foreach ($fastEquipment['addons'] as $addon) {
                        $laboratoryEquipmentAddon = new LaboratoryEquipmentAddon;
                        if (isset($addon['description'])) {
                            $laboratoryEquipmentAddon->description = $addon['description'];
                            $laboratoryEquipmentAddon->laboratory_equipment_id = $equipment->id;
                            $laboratoryEquipmentAddon->type = $addon['type']['name'];
                            $laboratoryEquipmentAddon->group = $addon['group']['name'];
                            $laboratoryEquipmentAddon->keyword_id = $this->getAddonKeyword($laboratoryEquipmentAddon, $equipment);

                            $laboratoryEquipmentAddon->save();
                        }
                    }
                }
            }
            */

            $laboratory->save();

            $laboratoryUpdateFast->response_code = $result->response_code;
            $laboratoryUpdateFast->source_laboratory_data = $data;
            $laboratoryUpdateFast->save();
        } else {
            $laboratoryUpdateFast->response_code = $result->response_code;
            $laboratoryUpdateFast->save();
        }


    }

    private function processFastFacilitiesResults($result, Fast $fast, LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast): void
    {
        if ($result->response_code == 200) {
            if (count($result->response_body['data']) > 0) {
                foreach ($result->response_body['data'] as $data) {
                    $laboratoryUpdateFast = LaboratoryUpdateFast::create([
                        'laboratory_update_group_fast_id' => $laboratoryUpdateGroupFast->id,
                        'laboratory_id' => $data['id'],
                    ]);

                    ProcessLaboratoryUpdateFast::dispatch($laboratoryUpdateFast);
                }

                if ($result->response_body['page']['current'] < $result->response_body['page']['last']) {
                    $facilitiesResult = $fast->facilitiesRequest($result->response_body['page']['next']);
                    $this->processFastFacilitiesResults($facilitiesResult, $fast, $laboratoryUpdateGroupFast);
                }
            }
        }
    }

}
