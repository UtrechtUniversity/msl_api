<?php

namespace App\Services;

use App\Clients\Fast\Fast;
use App\Jobs\ProcessCkanFlush;
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

    public function processFullLaboratoryUpdate()
    {
        $fast = new Fast;


    }
}
