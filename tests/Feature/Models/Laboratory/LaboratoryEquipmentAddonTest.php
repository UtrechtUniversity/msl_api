<?php

namespace Feature\Models\Laboratory;

use App\Models\Keyword;
use App\Models\Laboratory\LaboratoryEquipment;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryEquipmentAddonTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_equipment_relation(): void
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

        $addon = $equipment->laboratoryEquipmentAddons()->createQuietly([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
        ]);

        $this->assertInstanceOf(LaboratoryEquipment::class, $addon->laboratoryEquipment);
        $this->assertSame($equipment->id, $addon->laboratoryEquipment->id);
    }

    public function test_keyword_relation(): void
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

        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/lab',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->createQuietly([
            'value' => 'addon-term',
            'uri' => 'https://example.org/vocab/lab/addon-term',
            'level' => 0,
            'label' => 'Addon term',
        ]);

        $addon = $equipment->laboratoryEquipmentAddons()->createQuietly([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
            'keyword_id' => $keyword->id,
        ]);

        $this->assertInstanceOf(Keyword::class, $addon->keyword);
        $this->assertSame($keyword->id, $addon->keyword->id);
    }
}
