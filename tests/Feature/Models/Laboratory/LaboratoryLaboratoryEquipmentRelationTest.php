<?php

namespace Tests\Feature\Models\Laboratory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryLaboratoryEquipmentRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_laboratory_equipment_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();
        $laboratory = $this->makeLaboratory($organization, $manager);

        $equipment = $laboratory->laboratoryEquipment()->create(
            $this->minimalEquipmentAttributes()
        );

        $this->assertCount(1, $laboratory->fresh()->laboratoryEquipment);
        $this->assertTrue($laboratory->laboratoryEquipment->contains($equipment));
        $this->assertSame($laboratory->id, $equipment->laboratory->id);
    }
}
