<?php

namespace Tests\Feature\Models\VocabularyKeyword;

use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeywordKeywordSearchRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_keyword_search_relation(): void
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

        $search = $keyword->keywordSearch()->create([
            'search_value' => 'granite rock',
            'isSynonym' => true,
            'exclude_abstract_mapping' => false,
        ]);

        $this->assertCount(1, $keyword->fresh()->keywordSearch);
        $this->assertTrue($keyword->keywordSearch->contains($search));
        $this->assertSame($keyword->id, $search->keyword->id);
    }
}
