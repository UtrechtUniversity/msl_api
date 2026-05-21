<?php

namespace Tests\Feature\Models;

use App\Models\Laboratory;
use App\Models\LaboratoryManager;
use App\Models\LaboratoryOrganization;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryEquipmentAddonTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_equipment_relation(): void
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

        $laboratory = Laboratory::create([
            'laboratory_organization_id' => $organization->id,
            'laboratory_manager_id' => $manager->id,
            'fast_id' => 10,
            'msl_identifier' => 'test_lab_1',
            'lab_portal_name' => 'Portal',
            'lab_editor_name' => 'Editor',
            'msl_identifier_inputstring' => 'test_lab_1',
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

        $equipment = $laboratory->laboratoryEquipment()->create([
            'description' => 'Equipment description',
            'description_html' => '<p>Equipment description</p>',
            'category_name' => 'cat',
            'type_name' => 'type',
            'domain_name' => 'domain',
            'group_name' => 'group',
            'brand' => 'brand',
            'website' => 'https://eq.example.org',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'external_identifier' => 'eq-1',
            'name' => 'Equipment one',
        ]);

        $addon = $equipment->laboratoryEquipmentAddons()->create([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
        ]);

        $this->assertSame($equipment->id, $addon->laboratoryEquipment->id);
    }

    public function test_keyword_relation(): void
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

        $laboratory = Laboratory::create([
            'laboratory_organization_id' => $organization->id,
            'laboratory_manager_id' => $manager->id,
            'fast_id' => 10,
            'msl_identifier' => 'test_lab_1',
            'lab_portal_name' => 'Portal',
            'lab_editor_name' => 'Editor',
            'msl_identifier_inputstring' => 'test_lab_1',
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

        $equipment = $laboratory->laboratoryEquipment()->create([
            'description' => 'Equipment description',
            'description_html' => '<p>Equipment description</p>',
            'category_name' => 'cat',
            'type_name' => 'type',
            'domain_name' => 'domain',
            'group_name' => 'group',
            'brand' => 'brand',
            'website' => 'https://eq.example.org',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'external_identifier' => 'eq-1',
            'name' => 'Equipment one',
        ]);

        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/lab',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->create([
            'value' => 'addon-term',
            'uri' => 'https://example.org/vocab/lab/addon-term',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Addon term',
        ]);

        $addon = $equipment->laboratoryEquipmentAddons()->create([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
            'keyword_id' => $keyword->id,
        ]);

        $this->assertSame($keyword->id, $addon->keyword->id);
    }
}
