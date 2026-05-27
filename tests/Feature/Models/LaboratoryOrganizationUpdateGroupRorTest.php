<?php

namespace Tests\Feature\Models;

use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryOrganizationUpdateGroupRor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryOrganizationUpdateGroupRorTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_organization_update_rors_relation(): void
    {
        $organization = LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Org',
            'external_identifier' => 'ext-1',
        ]);

        $group = LaboratoryOrganizationUpdateGroupRor::create([]);

        $updateRor = $group->laboratoryOrganizationUpdateRors()->create([
            'laboratory_organization_id' => $organization->id,
            'response_code' => 200,
            'source_organization_data' => '{}',
        ]);

        $this->assertCount(1, $group->fresh()->laboratoryOrganizationUpdateRors);
        $this->assertTrue($group->laboratoryOrganizationUpdateRors->contains($updateRor));
        $this->assertInstanceOf(LaboratoryOrganizationUpdateGroupRor::class, $updateRor->laboratoryOrganizationUpdateGroupRor);
        $this->assertSame($group->id, $updateRor->laboratoryOrganizationUpdateGroupRor->id);
    }
}
