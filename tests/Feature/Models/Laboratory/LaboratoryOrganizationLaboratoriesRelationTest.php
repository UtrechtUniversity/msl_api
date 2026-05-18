<?php

namespace Tests\Feature\Models\Laboratory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryOrganizationLaboratoriesRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_organization_laboratories_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();

        $attributes = $this->laboratoryAttributes($organization, $manager);
        $attributes['msl_identifier'] = 'lab_under_org';

        $laboratory = $organization->laboratories()->create($attributes);

        $this->assertCount(1, $organization->fresh()->laboratories);
        $this->assertTrue($organization->laboratories->contains($laboratory));
        $this->assertSame($organization->id, $laboratory->laboratoryOrganization->id);
    }
}
