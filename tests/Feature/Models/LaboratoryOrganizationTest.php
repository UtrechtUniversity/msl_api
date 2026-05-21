<?php

namespace Tests\Feature\Models;

use App\Models\LaboratoryManager;
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

        $manager = LaboratoryManager::create([
            'email' => 'manager@example.org',
            'first_name' => 'Pat',
            'last_name' => 'Lee',
            'orcid' => '0000-0002-1825-0097',
            'address_street_1' => '1 Main St',
            'address_street_2' => '',
            'address_postalcode' => '1000AA',
            'address_city' => 'Amsterdam',
            'address_country_code' => 'NL',
            'address_country_name' => 'Netherlands',
            'affiliation_fast_id' => 1,
            'nationality_code' => 'NL',
            'nationality_name' => 'Dutch',
        ]);

        $laboratory = $organization->laboratories()->create([
            'laboratory_manager_id' => $manager->id,
            'fast_id' => 10,
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
            'address_country_name' => 'Netherlands',
            'latitude' => '52.0',
            'longitude' => '5.0',
            'altitude' => '0',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_id' => null,
            'fast_domain_name' => 'domain',
        ]);

        $this->assertCount(1, $organization->fresh()->laboratories);
        $this->assertTrue($organization->laboratories->contains($laboratory));
        $this->assertSame($organization->id, $laboratory->laboratoryOrganization->id);
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
        $this->assertSame($organization->id, $updateRor->laboratoryOrganization->id);
    }
}
