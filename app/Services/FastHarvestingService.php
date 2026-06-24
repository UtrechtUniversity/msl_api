<?php

namespace App\Services;

use App\Clients\Fast\Fast;
use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Laboratory\LaboratoryEquipmentAddon;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;

class FastHarvestingService
{
    public function removeExistingData(): void
    {
        // Truncating will reset primary keys etc. and delete all content. This will, however, not trigger deleted events.
        LaboratoryOrganization::truncate();
        LaboratoryContactPerson::truncate();
        LaboratoryManager::truncate();
        LaboratoryEquipmentAddon::truncate();

        // First, delete all existing laboratory data. So it synchs with CKAN.
        $laboratories = Laboratory::all();
        foreach ($laboratories as $laboratory) {
            $laboratory->delete();
        }

        Laboratory::truncate();
        LaboratoryEquipment::truncate();
    }

    public function processFullLaboratoryUpdate()
    {
        $fast = new Fast;


    }
}
