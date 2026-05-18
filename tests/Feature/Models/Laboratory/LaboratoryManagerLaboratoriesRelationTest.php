<?php

namespace Tests\Feature\Models\Laboratory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryManagerLaboratoriesRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_manager_laboratories_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();
        
        $attributes = $this->laboratoryAttributes($organization, $manager);
        $attributes['msl_identifier'] = 'lab_under_manager';

        $laboratory = $manager->laboratories()->create($attributes);

        $this->assertCount(1, $manager->fresh()->laboratories);
        $this->assertTrue($manager->laboratories->contains($laboratory));
        $this->assertSame($manager->id, $laboratory->laboratoryManager->id);
    }
}
