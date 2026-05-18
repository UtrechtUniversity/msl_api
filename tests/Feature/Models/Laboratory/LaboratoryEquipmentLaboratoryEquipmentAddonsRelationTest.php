<?php

namespace Tests\Feature\Models\Laboratory;

use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryEquipmentLaboratoryEquipmentAddonsRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_equipment_laboratory_equipment_addons_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();
        $laboratory = $this->makeLaboratory($organization, $manager);

        $equipmentKeyword = $this->makeVocabularyWithKeyword();
        $addonKeyword = $this->makeSecondKeyword($equipmentKeyword);

        $equipment = $laboratory->laboratoryEquipment()->create(
            $this->minimalEquipmentAttributes($equipmentKeyword)
        );

        $addon = $equipment->laboratoryEquipmentAddons()->create([
            'description' => 'Addon description',
            'type' => 'accessory',
            'group' => 'default',
            'keyword_id' => $addonKeyword->id,
        ]);

        $this->assertCount(1, $equipment->fresh()->laboratoryEquipmentAddons);
        $this->assertTrue($equipment->laboratoryEquipmentAddons->contains($addon));
        $this->assertSame($equipment->id, $addon->laboratoryEquipment->id);
        $this->assertSame($addonKeyword->id, $addon->keyword->id);
    }

    private function makeSecondKeyword(Keyword $first): Keyword
    {
        $vocabulary = $first->vocabulary;

        return $vocabulary->keywords()->create([
            'value' => 'addon-term',
            'uri' => 'https://example.org/vocab/lab/addon-term',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Addon term',
        ]);
    }
}
