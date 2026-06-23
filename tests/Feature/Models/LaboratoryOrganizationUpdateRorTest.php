<?php

namespace Tests\Feature\Models;

use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryOrganizationUpdateGroupRor;
use App\Models\LaboratoryOrganizationUpdateRor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryOrganizationUpdateRorTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_organization_update_group_ror_relation(): void
    {
        $organization = LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Org',
            'external_identifier' => 'ext-1',
        ]);

        $group = LaboratoryOrganizationUpdateGroupRor::create([]);

        $updateRor = LaboratoryOrganizationUpdateRor::create([
            'laboratory_organization_update_group_ror_id' => $group->id,
            'laboratory_organization_id' => $organization->id,
            'response_code' => 200,
            'source_organization_data' => '{}',
        ]);

        $this->assertInstanceOf(LaboratoryOrganizationUpdateGroupRor::class, $updateRor->laboratoryOrganizationUpdateGroupRor);
        $this->assertSame($group->id, $updateRor->laboratoryOrganizationUpdateGroupRor->id);
    }

    public function test_laboratory_organization_relation(): void
    {
        $organization = LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Org',
            'external_identifier' => 'ext-1',
        ]);

        $group = LaboratoryOrganizationUpdateGroupRor::create([]);

        $updateRor = LaboratoryOrganizationUpdateRor::create([
            'laboratory_organization_update_group_ror_id' => $group->id,
            'laboratory_organization_id' => $organization->id,
            'response_code' => 200,
            'source_organization_data' => '{}',
        ]);

        $this->assertInstanceOf(LaboratoryOrganization::class, $updateRor->laboratoryOrganization);
        $this->assertSame($organization->id, $updateRor->laboratoryOrganization->id);
    }
}
