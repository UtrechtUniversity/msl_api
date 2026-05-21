<?php

namespace Tests\Feature\Models;

use App\Models\LaboratoryManager;
use App\Models\LaboratoryOrganization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryManagerTest extends TestCase
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

        $laboratory = $manager->laboratories()->create([
            'laboratory_organization_id' => $organization->id,
            'fast_id' => 10,
            'msl_identifier' => 'lab_under_manager',
            'lab_portal_name' => 'Portal',
            'lab_editor_name' => 'Editor',
            'msl_identifier_inputstring' => 'lab_under_manager',
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

        $this->assertCount(1, $manager->fresh()->laboratories);
        $this->assertTrue($manager->laboratories->contains($laboratory));
        $this->assertSame($manager->id, $laboratory->laboratoryManager->id);
    }
}
