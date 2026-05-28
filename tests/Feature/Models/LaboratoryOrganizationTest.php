<?php

namespace Tests\Feature\Models;

use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryOrganizationUpdateGroupRor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratories_relation(): void
    {
        $organization = LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);

        $laboratory = $organization->laboratories()->create([
            'msl_identifier' => 'lab_under_org',
            'lab_portal_name' => 'Portal',
            'lab_editor_name' => 'Editor',
            'msl_identifier_inputstring' => 'lab_under_org',
            'original_domain' => 'example.org',
            'name' => 'Test Lab',
            'description' => 'Description',
            'description_html' => '<p>Description</p>',
            'website' => 'https://lab.example.org',
            'address_street_1' => '1 Lab Rd',
            'address_street_2' => '',
            'address_postalcode' => '2000BB',
            'address_city' => 'Utrecht',
            'address_country_code' => 'NL',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '0',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $this->assertCount(1, $organization->fresh()->laboratories);
        $this->assertTrue($organization->laboratories->contains($laboratory));
    }

    public function test_laboratory_organization_update_rors_relation(): void
    {
        $organization = LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Org',
            'external_identifier' => 'ext-1',
        ]);

        $group = LaboratoryOrganizationUpdateGroupRor::create([]);

        $updateRor = $organization->laboratoryOrganizationUpdateRors()->create([
            'laboratory_organization_update_group_ror_id' => $group->id,
            'response_code' => 200,
            'source_organization_data' => '{}',
        ]);

        $this->assertCount(1, $organization->fresh()->laboratoryOrganizationUpdateRors);
        $this->assertTrue($organization->laboratoryOrganizationUpdateRors->contains($updateRor));
    }
}
