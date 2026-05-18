<?php

namespace Tests\Feature\Models\VocabularyKeyword;

use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VocabularyKeywordsRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_vocabulary_keywords_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/materials',
            'display_name' => 'Materials',
        ]);

        $keyword = $vocabulary->keywords()->create([
            'value' => 'granite',
            'uri' => 'https://example.org/vocab/materials/granite',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Granite',
        ]);

        $this->assertCount(1, $vocabulary->fresh()->keywords);
        $this->assertTrue($vocabulary->keywords->contains($keyword));
        $this->assertSame($vocabulary->id, $keyword->vocabulary->id);
    }
}
