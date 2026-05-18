<?php

namespace Tests\Feature\Models\Laboratory\Support;

use App\Models\Keyword;
use App\Models\Laboratory;
use App\Models\LaboratoryManager;
use App\Models\LaboratoryOrganization;
use App\Models\Vocabulary;

trait CreatesLaboratoryFixtures
{
    protected function makeLaboratoryOrganization(): LaboratoryOrganization
    {
        return LaboratoryOrganization::create([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);
    }

    protected function makeLaboratoryManager(): LaboratoryManager
    {
        return LaboratoryManager::create([
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
    }

    protected function makeLaboratory(
        LaboratoryOrganization $organization,
        LaboratoryManager $manager
    ): Laboratory {
        return Laboratory::create($this->laboratoryAttributes($organization, $manager));
    }

    protected function laboratoryAttributes(
        LaboratoryOrganization $organization,
        LaboratoryManager $manager
    ): array {
        return [
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
        ];
        
    }

    protected function makeVocabularyWithKeyword(): Keyword
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/lab',
            'display_name' => 'Materials',
        ]);

        return $vocabulary->keywords()->create([
            'value' => 'widget',
            'uri' => 'https://example.org/vocab/lab/widget',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Widget',
        ]);
    }

    protected function minimalEquipmentAttributes(?Keyword $keyword = null): array
    {
        return [
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
            'keyword_id' => $keyword?->id,
        ];
    }
}
