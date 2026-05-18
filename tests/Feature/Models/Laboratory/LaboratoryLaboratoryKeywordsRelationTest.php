<?php

namespace Tests\Feature\Models\Laboratory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryLaboratoryKeywordsRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_laboratory_keywords_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();
        $laboratory = $this->makeLaboratory($organization, $manager);

        $laboratoryKeyword = $laboratory->laboratoryKeywords()->create([
            'value' => 'geology',
            'uri' => 'https://example.org/kw/geology',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryKeywords);
        $this->assertTrue($laboratory->laboratoryKeywords->contains($laboratoryKeyword));
        $this->assertSame($laboratory->id, $laboratoryKeyword->laboratory->id);
    }
}
