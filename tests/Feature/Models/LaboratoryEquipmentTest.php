<?php

namespace Tests\Feature\Models;

use App\Models\Keyword;
use App\Models\Laboratory;
use App\Models\LaboratoryEquipment;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryEquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_relation(): void
    {
        $laboratory = Laboratory::create([
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
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
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
        ]);

        $this->assertInstanceOf(Laboratory::class, $equipment->laboratory);
        $this->assertSame($laboratory->id, $equipment->laboratory->id);
    }

    public function test_keyword_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/lab',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->create([
            'value' => 'widget',
            'uri' => 'https://example.org/vocab/lab/widget',
            'level' => 0,
            'label' => 'Widget',
        ]);

        $equipment = LaboratoryEquipment::create([
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
            'keyword_id' => $keyword->id,
        ]);

        $this->assertInstanceOf(Keyword::class, $equipment->keyword);
        $this->assertSame($keyword->id, $equipment->keyword->id);
    }

    public function test_laboratory_equipment_addons_relation(): void
    {
        $equipment = LaboratoryEquipment::create([
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
        ]);

        $addon = $equipment->laboratoryEquipmentAddons()->create([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
        ]);

        $this->assertCount(1, $equipment->fresh()->laboratoryEquipmentAddons);
        $this->assertTrue($equipment->laboratoryEquipmentAddons->contains($addon));
    }
}
